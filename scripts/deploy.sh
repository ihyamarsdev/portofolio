#!/usr/bin/env bash
#
# Portofolio — idempotent deployment script (designed for webhook use).
#
# Usage:
#   bash scripts/deploy.sh            # deploy only when origin/main has new commits
#   bash scripts/deploy.sh --force    # force full pipeline even without new commits
#
# Recommended webhook command:
#   bash /home/ihya/project/portofolio/scripts/deploy.sh
#
# Optional sudoers entry (so service restart works without a password):
#   ihya ALL=(ALL) NOPASSWD: /usr/bin/systemctl restart portofolio-web portofolio-queue
#
set -euo pipefail

PROJECT_DIR="/home/ihya/project/portofolio"
BRANCH="main"
LOG_FILE="${PROJECT_DIR}/storage/logs/deploy.log"
LOCK_FILE="/tmp/portofolio-deploy.lock"
HEALTH_URL="http://127.0.0.1:8888/"
SERVICES=("portofolio-web" "portofolio-queue")
RUNNER_HOME="${RUNNER_HOME:-/home/ihya}"

# ---------------------------------------------------------------------------
# Logging (tee to console + file)
# ---------------------------------------------------------------------------
mkdir -p "$(dirname "$LOG_FILE")"
exec > >(tee -a "$LOG_FILE") 2>&1

log()  { echo "[$(date '+%Y-%m-%d %H:%M:%S')] $*"; }
fail() { log "DEPLOY FAILED: $*"; exit 1; }

# ---------------------------------------------------------------------------
# Prevent concurrent deploys
# ---------------------------------------------------------------------------
exec 9>"$LOCK_FILE"
if ! flock -n 9; then
    log "Another deployment is already running — exiting."
    exit 0
fi

FORCE="${1:-}"
log "=== Deploy started (force: ${FORCE:-no}) ==="

cd "$PROJECT_DIR"

# ---------------------------------------------------------------------------
# Resolve node/npm (installed via nvm)
# ---------------------------------------------------------------------------
NODE_BIN_DIR="$(ls -d "${RUNNER_HOME}"/.nvm/versions/node/*/bin 2>/dev/null | sort -V | tail -1 || true)"
if [ -n "${NODE_BIN_DIR}" ] && [ -x "${NODE_BIN_DIR}/npm" ]; then
    export PATH="${NODE_BIN_DIR}:${PATH}"
fi
if ! command -v npm >/dev/null 2>&1; then
    log "WARNING: npm not found — asset build will be skipped."
fi

# ---------------------------------------------------------------------------
# Detect incoming changes
# ---------------------------------------------------------------------------
OLD_SHA="$(git rev-parse HEAD)"
git fetch origin "${BRANCH}"
NEW_SHA="$(git rev-parse "origin/${BRANCH}")"

if [ "${OLD_SHA}" = "${NEW_SHA}" ] && [ "${FORCE}" != "--force" ]; then
    log "Already up to date (${OLD_SHA}). Nothing to do."
    log "=== Deploy finished (no-op) ==="
    exit 0
fi

CHANGED_FILES="$(git diff --name-only "${OLD_SHA}" "${NEW_SHA}" || true)"

needs_composer=false
needs_build=false
if echo "${CHANGED_FILES}" | grep -q '^composer\.lock$'; then
    needs_composer=true
fi
if echo "${CHANGED_FILES}" | grep -Eq '^(package\.json|package-lock\.json|vite\.config\..*|resources/)'; then
    needs_build=true
fi

# ---------------------------------------------------------------------------
# Pull (keep working tree clean of tracked build artifacts first)
# ---------------------------------------------------------------------------
# The committed public/build/manifest.json changes on every local build and
# the server rebuilds it anyway — discard it so the pull never conflicts.
git checkout -- public/build 2>/dev/null || true

if [ -n "$(git status --porcelain --untracked-files=no)" ]; then
    fail "Working tree has uncommitted changes to tracked files; refusing to pull."
fi

log "Pulling ${OLD_SHA:0:7} -> ${NEW_SHA:0:7}"
git merge --ff-only "origin/${BRANCH}" || fail "git merge --ff-only failed."

# ---------------------------------------------------------------------------
# PHP dependencies (only when composer.lock changed)
# ---------------------------------------------------------------------------
if [ "${needs_composer}" = true ]; then
    log "composer.lock changed — running composer install"
    composer install --no-interaction --prefer-dist --optimize-autoloader \
        || fail "composer install failed."
else
    log "composer.lock unchanged — skipping composer install"
fi

# ---------------------------------------------------------------------------
# Frontend build (always: cheap ~4s and guarantees manifest/assets match)
# ---------------------------------------------------------------------------
if command -v npm >/dev/null 2>&1; then
    if [ "${needs_build}" = true ]; then
        log "Frontend sources changed — running npm ci + build"
        (npm ci --no-audit --no-fund || npm install --no-audit --no-fund) \
            || fail "npm install failed."
    else
        log "Running npm build only"
    fi
    npm run build || fail "npm run build failed."
else
    log "Skipping frontend build (npm unavailable)"
fi

# ---------------------------------------------------------------------------
# Database migrations (idempotent — no-op when nothing pending)
# ---------------------------------------------------------------------------
log "Running migrations"
php artisan migrate --force || fail "migrate failed."

# ---------------------------------------------------------------------------
# Caches & storage link
# ---------------------------------------------------------------------------
log "Refreshing caches"
php artisan config:cache      || fail "config:cache failed."
php artisan route:cache       || fail "route:cache failed."
php artisan view:cache        || fail "view:cache failed."
php artisan filament:cache-components || log "WARNING: filament:cache-components failed (non-fatal)."
php artisan storage:link >/dev/null 2>&1 || true

# ---------------------------------------------------------------------------
# Restart runtime services
# ---------------------------------------------------------------------------
log "Restarting services: ${SERVICES[*]}"
if sudo -n systemctl restart "${SERVICES[@]}" 2>/dev/null; then
    log "Services restarted."
else
    log "WARNING: could not restart services (sudo unavailable?)."
    log "         Run manually: sudo systemctl restart ${SERVICES[*]}"
fi
sleep 3

# ---------------------------------------------------------------------------
# Health check
# ---------------------------------------------------------------------------
if curl -fsS -o /dev/null --max-time 10 "${HEALTH_URL}"; then
    log "Health check OK (${HEALTH_URL})"
else
    fail "Health check FAILED — check storage/logs/laravel.log and journalctl -u portofolio-web"
fi

log "=== Deploy finished successfully (${NEW_SHA:0:7}) ==="


