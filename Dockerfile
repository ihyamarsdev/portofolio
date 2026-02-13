FROM dunglas/frankenphp:php8.4-alpine

# Set working directory
WORKDIR /app

# Install extensions
RUN install-php-extensions \
    pcntl \
    bcmath \
    gd \
    intl \
    zip \
    opcache \
    pdo_pgsql \
    pgsql \
    exif \
    && apk add --no-cache nodejs npm 

# Composer installation
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

# Copy dependency files
COPY composer.json composer.lock package.json package-lock.json ./

RUN composer install --no-dev --no-autoloader --no-scripts && \
    npm ci

# Copy application files
COPY . .

# Finish composer installation and build frontend
RUN composer dump-autoload --optimize && \
    npm run build


RUN php artisan storage:link && \
    php artisan optimize:clear && \
    php artisan optimize

ENTRYPOINT ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8888"]
