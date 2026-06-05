# syntax=docker/dockerfile:1.7

# =============================================================================
# Production Dockerfile for Laravel 13 + FrankenPHP
# Pattern: Laravel optimized multi-stage build
# - dependency files first for stable Docker cache
# - frontend assets isolated in Node stage
# - runtime copies only needed Laravel files/artifacts
# - PHP extensions installed in final runtime stage
# =============================================================================

FROM composer:2 AS vendor

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

COPY app ./app
COPY bootstrap ./bootstrap
COPY config ./config
COPY database ./database
COPY routes ./routes
COPY resources/views ./resources/views
COPY artisan ./artisan

RUN composer dump-autoload --no-dev --optimize --classmap-authoritative --no-scripts


FROM node:22-alpine AS frontend

WORKDIR /app

ENV NODE_ENV=development \
    PUPPETEER_SKIP_DOWNLOAD=true

COPY package.json package-lock.json ./

RUN --mount=type=cache,target=/root/.npm,sharing=locked \
    npm ci --fetch-retries=5 --fetch-retry-factor=2 --fetch-retry-mintimeout=10000 --fetch-retry-maxtimeout=120000

COPY app ./app
COPY bootstrap ./bootstrap
COPY config ./config
COPY routes ./routes
COPY resources ./resources
COPY public ./public
COPY vite.config.js ./vite.config.js
COPY artisan ./artisan
COPY --from=vendor /app/vendor ./vendor

RUN npm run build \
    && rm -rf /tmp/*


FROM dunglas/frankenphp:php8.4 AS runtime

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

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libicu76 \
        libpq5 \
        libzip5 \
        netcat-openbsd \
    && install-php-extensions \
        bcmath \
        intl \
        pcntl \
        pdo_pgsql \
        redis \
        zip \
    && mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini" \
    && rm -rf /var/lib/apt/lists/* /tmp/*

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
    && chown -R www-data:www-data /app/storage /app/bootstrap/cache \
    && find /app/storage -type d -exec chmod 775 {} \; \
    && find /app/bootstrap/cache -type d -exec chmod 775 {} \; \
    && rm -rf \
        /root/.composer \
        /tmp/*

HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
    CMD php -r "$status = @file_get_contents('http://127.0.0.1:8888/'); exit($status === false ? 1 : 0);"

EXPOSE 8888

ENTRYPOINT ["docker-entrypoint.sh"]
