# =============================================================================
# Multi-Stage Dockerfile for Laravel + FrankenPHP (Production)
# Official FrankenPHP image — no Octane
#
# Build: DOCKER_BUILDKIT=1 docker compose build
# =============================================================================

# -----------------------------------------------------------------------------
# Stage 1: Composer — install PHP dependencies
# (harus pertama karena node-builder butuh vendor/)
# -----------------------------------------------------------------------------
FROM composer:2 AS composer-builder

WORKDIR /app

COPY composer.json composer.lock ./
RUN --mount=type=cache,target=/tmp/composer-cache,sharing=locked \
    composer install \
    --no-dev \
    --no-scripts \
    --no-autoloader \
    --prefer-dist \
    --no-interaction \
    --ignore-platform-reqs

COPY . .
RUN composer dump-autoload --classmap-authoritative --no-dev

# -----------------------------------------------------------------------------
# Stage 2: Node — build frontend assets
# Butuh vendor/ dari composer-builder agar Vite bisa resolve Filament CSS
# -----------------------------------------------------------------------------
FROM node:22-alpine AS node-builder

WORKDIR /app

COPY package.json package-lock.json ./
RUN --mount=type=cache,target=/root/.npm,sharing=locked \
    npm ci

COPY . .
# Copy vendor dari composer-builder agar Vite resolve Filament CSS
COPY --from=composer-builder /app/vendor /app/vendor
RUN npm run build

# -----------------------------------------------------------------------------
# Stage 3: Runtime — official FrankenPHP Alpine image
# -----------------------------------------------------------------------------
FROM dunglas/frankenphp:php8.4-alpine

# Install PHP extensions required by Laravel
RUN install-php-extensions \
    pdo_pgsql \
    mbstring \
    opcache \
    intl \
    zip \
    gd \
    bcmath \
    pcntl \
    sockets \
    redis \
    exif \
    ctype

# Enable PHP production settings
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# Install netcat for service wait in entrypoint
RUN apk add --no-cache netcat-openbsd

WORKDIR /app

# Copy app dari composer-builder (sudah ada vendor/, autoload, dll)
COPY --from=composer-builder /app /app
# Timpa dengan built assets dari node-builder
COPY --from=node-builder /app/public/build /app/public/build

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Create required Laravel storage directories
RUN mkdir -p storage/framework/cache \
             storage/framework/sessions \
             storage/framework/views \
             storage/logs \
             bootstrap/cache

# SERVER_NAME=":8888" → FrankenPHP listen HTTP saja, Traefik handle TLS
ENV SERVER_NAME=":8888" \
    PHP_OPCACHE_ENABLE=1 \
    PHP_OPCACHE_MEMORY_CONSUMPTION=128

EXPOSE 8888

HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
    CMD wget --no-verbose --tries=1 --spider http://localhost:8888/ || exit 1

ENTRYPOINT ["docker-entrypoint.sh"]
