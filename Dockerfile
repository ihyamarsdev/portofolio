FROM dunglas/frankenphp:php8.4-alpine

# Install extensions
RUN install-php-extensions \
    pcntl \
    bcmath \
    gd \
    intl \
    zip \
    opcache \
    pdo_pgsql \
    pgsql 

# Set working directory
WORKDIR /app

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
