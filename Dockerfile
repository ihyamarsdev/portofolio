FROM dunglas/frankenphp:php8.4-alpine

WORKDIR /app

# Install system dependencies
RUN apk add --no-cache \
    curl \
    wget \
    netcat-openbsd \
    nodejs \
    npm

# Install PHP extensions using the official helper script
RUN install-php-extensions \
    pdo_pgsql \
    pgsql \
    gd \
    intl \
    zip \
    bcmath \
    sockets \
    exif \
    pcntl \
    opcache \
    redis

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
