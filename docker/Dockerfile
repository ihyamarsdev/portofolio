# syntax=docker/dockerfile:1.7

# =============================================================================
# Production Dockerfile for Laravel 13 + FrankenPHP
# Goals:
# - leaner runtime image
# - predictable caches
# - keep required runtime features: Browsershot/Puppeteer + Mermaid CLI
# - avoid dev/test/docs payload in final image
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

COPY . .

RUN composer dump-autoload --no-dev --optimize --classmap-authoritative


FROM node:22-alpine AS frontend

WORKDIR /app

ENV NODE_ENV=development \
    PUPPETEER_SKIP_DOWNLOAD=true

COPY package.json package-lock.json ./

RUN --mount=type=cache,target=/root/.npm,sharing=locked \
    npm ci --fetch-retries=5 --fetch-retry-factor=2 --fetch-retry-mintimeout=10000 --fetch-retry-maxtimeout=120000

COPY resources ./resources
COPY public ./public
COPY vite.config.js ./vite.config.js
COPY package.json ./package.json
COPY artisan ./artisan
COPY bootstrap ./bootstrap
COPY config ./config
COPY app ./app
COPY routes ./routes
COPY --from=vendor /app/vendor /app/vendor

RUN npm run build \
    && rm -rf /tmp/*


FROM node:22-alpine AS runtime-node

WORKDIR /app

ENV NODE_ENV=production \
    PUPPETEER_SKIP_DOWNLOAD=true

COPY package.json package-lock.json ./

RUN --mount=type=cache,target=/root/.npm,sharing=locked \
    npm ci --omit=dev --fetch-retries=5 --fetch-retry-factor=2 --fetch-retry-mintimeout=10000 --fetch-retry-maxtimeout=120000 \
    && npm cache clean --force


FROM dunglas/frankenphp:php8.4-alpine AS runtime

WORKDIR /app

ENV APP_ENV=production \
    SERVER_NAME=":8888" \
    PHP_OPCACHE_ENABLE=1 \
    PHP_OPCACHE_MEMORY_CONSUMPTION=128 \
    PHP_OPCACHE_INTERNED_STRINGS_BUFFER=16 \
    PHP_OPCACHE_MAX_ACCELERATED_FILES=20000 \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS=0 \
    PHP_MEMORY_LIMIT=256M \
    LOG_CHANNEL=stderr \
    PUPPETEER_SKIP_DOWNLOAD=true \
    PUPPETEER_EXECUTABLE_PATH=/usr/bin/chromium-browser \
    BROWSERSHOT_CHROME_PATH=/usr/bin/chromium-browser

RUN install-php-extensions \
    bcmath \
    ctype \
    exif \
    gd \
    intl \
    mbstring \
    opcache \
    pcntl \
    pdo_pgsql \
    redis \
    sockets \
    zip

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN apk add --no-cache \
    chromium \
    freetype \
    harfbuzz \
    nss \
    netcat-openbsd \
    nodejs \
    ttf-freefont

COPY --from=vendor /app /app
COPY --from=frontend /app/public/build /app/public/build
COPY --from=runtime-node /app/node_modules /app/node_modules
COPY docker/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

RUN chmod +x /usr/local/bin/docker-entrypoint.sh \
    && mkdir -p \
        bootstrap/cache \
        storage/app/temp \
        storage/framework/cache \
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
    CMD node -e "fetch('http://127.0.0.1:8888/').then(r=>process.exit(r.ok?0:1)).catch(()=>process.exit(1))"

EXPOSE 8888

ENTRYPOINT ["docker-entrypoint.sh"]
