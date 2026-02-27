FROM alpine:3.21

WORKDIR /app

# Install system dependencies + PHP CLI for build-time (Composer/artisan)
RUN apk add --no-cache \
    curl \
    wget \
    netcat-openbsd \
    nodejs \
    npm \
    libpq \
    libstdc++ \
    php84 \
    php84-cli \
    php84-pdo \
    php84-pdo_pgsql \
    php84-pgsql \
    php84-mbstring \
    php84-tokenizer \
    php84-xml \
    php84-xmlwriter \
    php84-dom \
    php84-openssl \
    php84-fileinfo \
    php84-ctype \
    php84-session \
    php84-simplexml \
    php84-phar \
    php84-zip \
    php84-intl \
    php84-xmlreader \
    php84-gd \
    php84-iconv \
    php84-exif \
    php84-bcmath \
    php84-pcntl \
    php84-sockets \
    php84-opcache && \
    ln -sf /usr/bin/php84 /usr/bin/php

# Install FrankenPHP (musl build — compatible with Alpine, used as Octane server)
RUN curl -L https://github.com/dunglas/frankenphp/releases/latest/download/frankenphp-linux-x86_64-musl \
    -o /usr/local/bin/frankenphp \
    && chmod +x /usr/local/bin/frankenphp

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy dependency files
COPY composer.json composer.lock package.json package-lock.json ./

# Install dependencies
RUN composer install --no-dev --no-autoloader --no-scripts && \
    npm ci

# Copy application files
COPY . .

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

# Build assets and optimize
RUN composer dump-autoload --optimize && \
    npm run build

# Create storage link
RUN php artisan storage:link

# Clear file-based caches only (skip database cache)
RUN php artisan config:clear && \
    php artisan route:clear && \
    php artisan view:clear

EXPOSE 8888

ENV OCTANE_SERVER=frankenphp

ENTRYPOINT ["docker-entrypoint.sh"]
