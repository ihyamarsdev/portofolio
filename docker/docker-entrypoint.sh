#!/bin/sh
set -eu

wait_for_tcp() {
    host="$1"
    port="$2"
    name="$3"
    timeout="${4:-60}"
    elapsed=0

    echo "Waiting for ${name} (${host}:${port})..."
    while ! nc -z "$host" "$port" >/dev/null 2>&1; do
        elapsed=$((elapsed + 1))
        if [ "$elapsed" -ge "$timeout" ]; then
            echo "Timed out waiting for ${name} after ${timeout}s" >&2
            exit 1
        fi
        sleep 1
    done
    echo "${name} is ready."
}

if [ "${WAIT_FOR_DB:-true}" = "true" ]; then
    wait_for_tcp "${DB_HOST:-db}" "${DB_PORT:-5432}" "PostgreSQL" "${DB_WAIT_TIMEOUT:-60}"
fi

if [ "${WAIT_FOR_REDIS:-true}" = "true" ]; then
    wait_for_tcp "${REDIS_HOST:-redis}" "${REDIS_PORT:-6379}" "Redis" "${REDIS_WAIT_TIMEOUT:-60}"
fi

mkdir -p \
    bootstrap/cache \
    storage/app/temp \
    storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs

if [ "${APP_ENV:-production}" = "production" ]; then
    echo "Caching Laravel bootstrap artifacts..."
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache
else
    php artisan config:clear || true
    php artisan route:clear || true
    php artisan view:clear || true
fi

php artisan storage:link --force || true
php artisan filament:assets --no-interaction || true

if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    echo "Running database migrations..."
    php artisan migrate --force
fi

if [ "${RUN_QUEUE_WORKER:-false}" = "true" ]; then
    echo "RUN_QUEUE_WORKER=true is not supported in this container. Run a dedicated queue worker service instead." >&2
    exit 1
fi

if [ "${RUN_SCHEDULER:-false}" = "true" ]; then
    echo "RUN_SCHEDULER=true is not supported in this container. Run a dedicated scheduler service instead." >&2
    exit 1
fi

echo "Starting FrankenPHP..."
exec frankenphp run --config /etc/caddy/Caddyfile --adapter caddyfile
