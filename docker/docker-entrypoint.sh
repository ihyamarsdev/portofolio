#!/bin/sh
set -e

# Wait for PostgreSQL to be ready
echo "Waiting for PostgreSQL..."
while ! nc -z "${DB_HOST:-db}" 5432; do
    sleep 1
done
echo "PostgreSQL is ready!"

# Wait for Redis to be ready
echo "Waiting for Redis..."
while ! nc -z "${REDIS_HOST:-redis}" 6379; do
    sleep 1
done
echo "Redis is ready!"

# Run migrations
if [ "$APP_ENV" = "production" ]; then
    echo "Running migrations..."
    php artisan migrate --force
fi

# Cache config/routes/views untuk performa production
echo "Caching config, routes, and views..."
php artisan storage:link --force || true
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start FrankenPHP (Caddy + PHP)
echo "Starting FrankenPHP..."
exec frankenphp run --config /etc/caddy/Caddyfile --adapter caddyfile
