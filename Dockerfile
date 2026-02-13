FROM dunglas/frankenphp:php8.4-alpine

# Set working directory
WORKDIR /app

# Install extensions
RUN docker-php-ext-install \
    bcmath \
    gd \
    intl \
    pdo_pgsql \
    pgsql \
    zip \
    exif


# Composer installation
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy dependency files
COPY composer.json composer.lock ./

# Install dependencies
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --no-dev --no-autoloader --no-scripts

# Copy application files
COPY . .

# Finish composer installation
RUN composer dump-autoload --optimize

# Set permissions (optional but good for some setups)
RUN chmod -R 777 storage bootstrap/cache

RUN php artisan storage:link

ENTRYPOINT ["php", "artisan", "octane:start", "--server=frankenphp", "--host=0.0.0.0", "--port=8888"]
