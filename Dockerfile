FROM alpine:3.21

WORKDIR /app

# Install PHP, extensions, and build tools
RUN apk add --no-cache \
    curl \
    nodejs \
    npm \
    php84 \
    php84-cli \
    php84-common \
    php84-ctype \
    php84-curl \
    php84-dom \
    php84-exif \
    php84-fileinfo \
    php84-gd \
    php84-iconv \
    php84-intl \
    php84-mbstring \
    php84-opcache \
    php84-openssl \
    php84-pcntl \
    php84-pdo \
    php84-pdo_pgsql \
    php84-pgsql \
    php84-phar \
    php84-posix \
    php84-session \
    php84-simplexml \
    php84-tokenizer \
    php84-xml \
    php84-xmlreader \
    php84-xmlwriter \
    php84-zip \
    php84-bcmath

# Symlink PHP
RUN ln -sf /usr/bin/php84 /usr/bin/php

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Install FrankenPHP
RUN curl -L https://github.com/dunglas/frankenphp/releases/latest/download/frankenphp-linux-x86_64 -o /usr/local/bin/frankenphp \
    && chmod +x /usr/local/bin/frankenphp

# Copy dependency files
COPY composer.json composer.lock package.json package-lock.json ./

# Install dependencies
RUN composer install --no-dev --no-autoloader --no-scripts && \
    npm ci

# Copy application files
COPY . .

# Build assets and optimize
RUN composer dump-autoload --optimize && \
    npm run build

RUN php artisan storage:link && \
    php artisan optimize

EXPOSE 8888

ENTRYPOINT ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8888"]
