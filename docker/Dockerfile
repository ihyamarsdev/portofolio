# syntax=docker/dockerfile:1.7

# =============================================================================
# Production Dockerfile for Laravel 13 + FrankenPHP
# Optimized for fast rebuilds:
# - Base PHP runtime image with pre-compiled extensions separated
# - Composer dependencies installed in vendor-deps stage
# - Frontend stage decoupled from post-autoload vendor and non-essential app files
# - Filament assets pre-compiled at build time
# - Final runtime image copies only necessary runtime files
# =============================================================================

ARG PHP_BASE_IMAGE=portofolio-php-base:8.4

# -----------------------------------------------------------------------------
# Stage 1: Vendor Dependencies
# -----------------------------------------------------------------------------
FROM composer:2 AS vendor-deps

WORKDIR /app

COPY composer.json composer.lock ./

RUN --mount=type=cache,target=/tmp/composer-cache,sharing=locked \
    composer install \
        --no-dev \
        --prefer-dist \
        --no-interaction \
        --no-progress \
        --optimize-autoloader \
        --classmap-authoritative \
        --ignore-platform-reqs \
        --no-scripts

# -----------------------------------------------------------------------------
# Stage 2: Vendor Autoload (Full App Autoload Generation)
# -----------------------------------------------------------------------------
FROM vendor-deps AS vendor

COPY app ./app
COPY bootstrap ./bootstrap
COPY config ./config
COPY database ./database
COPY routes ./routes
COPY resources/views ./resources/views
COPY artisan ./artisan

RUN composer dump-autoload --no-dev --optimize --classmap-authoritative --no-scripts

# -----------------------------------------------------------------------------
# Stage 3: Frontend Asset Compilation
# -----------------------------------------------------------------------------
FROM node:22-alpine AS frontend

WORKDIR /app

ENV NODE_ENV=development \
    PUPPETEER_SKIP_DOWNLOAD=true \
    PLAYWRIGHT_SKIP_BROWSER_DOWNLOAD=1

COPY package.json package-lock.json ./

RUN --mount=type=cache,target=/root/.npm,sharing=locked \
    npm ci --fetch-retries=5 --fetch-retry-factor=2 --fetch-retry-mintimeout=10000 --fetch-retry-maxtimeout=120000

COPY vite.config.js ./vite.config.js
COPY resources ./resources
COPY public ./public
COPY app/Filament ./app/Filament
COPY app/Livewire ./app/Livewire

# Copy only the vendor assets required for Tailwind @import and @source
COPY --from=vendor-deps /app/vendor/filament ./vendor/filament
COPY --from=vendor-deps /app/vendor/awcodes/richer-editor ./vendor/awcodes/richer-editor
COPY --from=vendor-deps /app/vendor/laravel/framework/src/Illuminate/Pagination/resources ./vendor/laravel/framework/src/Illuminate/Pagination/resources

RUN npm run build \
    && rm -rf /tmp/*

# -----------------------------------------------------------------------------
# Stage 4: Production Runtime Image
# -----------------------------------------------------------------------------
FROM ${PHP_BASE_IMAGE} AS runtime

WORKDIR /app

ENV APP_ENV=production \
    SERVER_NAME=":8888" \
    PHP_OPCACHE_ENABLE=1 \
    PHP_OPCACHE_MEMORY_CONSUMPTION=128 \
    PHP_OPCACHE_INTERNED_STRINGS_BUFFER=16 \
    PHP_OPCACHE_MAX_ACCELERATED_FILES=20000 \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS=0 \
    PHP_MEMORY_LIMIT=256M \
    LOG_CHANNEL=stderr

COPY composer.json composer.lock ./
COPY --from=vendor /app/vendor ./vendor
COPY app ./app
COPY bootstrap ./bootstrap
COPY config ./config
COPY database/migrations ./database/migrations
COPY public ./public
COPY resources/views ./resources/views
COPY routes ./routes
COPY artisan ./artisan
COPY --from=frontend /app/public/build ./public/build
COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

RUN chmod +x /usr/local/bin/docker-entrypoint.sh \
    && mkdir -p \
        bootstrap/cache \
        storage/app/public \
        storage/app/temp \
        storage/framework/cache \
        storage/framework/cache/data \
        storage/framework/sessions \
        storage/framework/views \
        storage/logs \
    && APP_KEY=base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA= php artisan package:discover --ansi \
    && APP_KEY=base64:AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA= php artisan filament:assets --no-interaction \
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && find /app/storage -type d -exec chmod 775 {} \; \
    && find /app/bootstrap/cache -type d -exec chmod 775 {} \; \
    && rm -rf /root/.composer /tmp/*

HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
    CMD php -r "$status = @file_get_contents('http://127.0.0.1:8888/'); exit($status === false ? 1 : 0);"

EXPOSE 8888

ENTRYPOINT ["docker-entrypoint.sh"]
