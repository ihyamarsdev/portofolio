# =============================================================================
# Speed-Optimized Multi-Stage Dockerfile for Laravel + Octane (FrankenPHP)
# Optimized for FAST rebuilds with BuildKit cache mounts
# Runs as root for maximum performance and simplicity
# =============================================================================
#
# Build with: DOCKER_BUILDKIT=1 docker build -t portofolio-app .
#
# Performance:
# - First build: ~3-5 min (downloads all dependencies)
# - Subsequent (no deps change): ~30-60 sec (uses layer cache)
# - Subsequent (deps changed): ~1-2 min (uses package cache)
# =============================================================================

# -----------------------------------------------------------------------------
# Stage 1: Builder
# Contains all build tools - creates artifacts for runtime stage
# -----------------------------------------------------------------------------
FROM alpine:3.21 AS builder

WORKDIR /app

# Install all system dependencies needed for building
RUN apk add --no-cache \
    curl \
    nodejs \
    npm \
    php84 \
    php84-cli \
    php84-pdo \
    php84-pdo_pgsql \
    php84-mbstring \
    php84-tokenizer \
    php84-xml \
    php84-xmlreader \
    php84-dom \
    php84-openssl \
    php84-fileinfo \
    php84-ctype \
    php84-session \
    php84-simplexml \
    php84-phar \
    php84-zip \
    php84-intl \
    php84-gd \
    php84-iconv \
    php84-exif \
    php84-bcmath \
    php84-pcntl \
    php84-sockets \
    php84-opcache && \
    ln -sf /usr/bin/php84 /usr/bin/php

# Install Composer and FrankenPHP once
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer
RUN FRANKENPHP_VERSION=$(curl -s https://api.github.com/repos/php/frankenphp/releases/latest | grep '"tag_name"' | sed -E 's/.*"([^"]+)".*/\1/') && \
    curl -L "https://github.com/php/frankenphp/releases/download/${FRANKENPHP_VERSION}/frankenphp-linux-x86_64" \
    -o /usr/local/bin/frankenphp && \
    chmod +x /usr/local/bin/frankenphp

# Copy PHP dependencies FIRST (before source code) for optimal layer caching
COPY composer.json composer.lock ./
# Use BuildKit cache mount for Composer - persists across builds!
RUN --mount=type=cache,target=/tmp/composer-cache,sharing=locked \
    composer install \
    --no-dev \
    --no-autoloader \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction

# Copy npm dependencies SECOND (before source code) for optimal layer caching
COPY package.json package-lock.json ./
# Use BuildKit cache mount for npm - persists across builds!
RUN --mount=type=cache,target=/root/.npm,sharing=locked \
    npm ci

# Now copy source code (changes frequently, so comes last)
COPY . .

# Copy entrypoint to /usr/local/bin
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Build everything in one layer
RUN composer dump-autoload --classmap-authoritative && \
    npm run build && \
    php artisan storage:link && \
    php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear

# -----------------------------------------------------------------------------
# Stage 2: Runtime
# Minimal production image with only runtime essentials
# -----------------------------------------------------------------------------
FROM alpine:3.21 AS runtime

# Install runtime dependencies (no build tools)
RUN apk add --no-cache \
    libpq \
    libstdc++ \
    libzip \
    libjpeg-turbo \
    libpng \
    freetype \
    icu-libs \
    wget \
    netcat-openbsd \
    php84 \
    php84-cli \
    php84-fpm \
    php84-pdo \
    php84-pdo_pgsql \
    php84-mbstring \
    php84-tokenizer \
    php84-xml \
    php84-xmlwriter \
    php84-xmlreader \
    php84-dom \
    php84-openssl \
    php84-fileinfo \
    php84-ctype \
    php84-session \
    php84-simplexml \
    php84-zip \
    php84-intl \
    php84-gd \
    php84-iconv \
    php84-exif \
    php84-bcmath \
    php84-pcntl \
    php84-sockets \
    php84-opcache \
    php84-curl && \
    ln -sf /usr/bin/php84 /usr/bin/php

WORKDIR /app

# Copy ONLY what's needed from builder (no node_modules, no composer)
COPY --from=builder /app /app
COPY --from=builder /usr/local/bin/frankenphp /usr/local/bin/frankenphp
COPY --from=builder /usr/local/bin/docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh

# Create storage directories
RUN mkdir -p storage/framework/cache \
    storage/framework/sessions \
    storage/framework/views \
    storage/logs \
    bootstrap/cache

# No USER directive - runs as root for simplicity and speed

EXPOSE 8888

ENV OCTANE_SERVER=frankenphp \
    PHP_OPCACHE_ENABLE=1 \
    PHP_OPCACHE_MEMORY_CONSUMPTION=128

HEALTHCHECK --interval=30s --timeout=10s --start-period=60s --retries=3 \
    CMD wget --no-verbose --tries=1 --spider http://localhost:8888/ || exit 1

ENTRYPOINT ["docker-entrypoint.sh"]
