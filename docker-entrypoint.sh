#!/bin/sh
set -e

# Wait for PostgreSQL to be ready
echo "Waiting for PostgreSQL..."
while ! nc -z db 5432; do
    sleep 1
done
echo "PostgreSQL is ready!"

# Wait for Redis to be ready
echo "Waiting for Redis..."
while ! nc -z redis 6379; do
    sleep 1
done
echo "Redis is ready!"

# Run migrations if needed (only in production)
if [ "$APP_ENV" = "production" ]; then
    echo "Running migrations..."
    php artisan migrate --force
fi

# Start Octane with FrankenPHP without workers
echo "Starting FrankenPHP Octane server (no worker mode)..."
exec php artisan octane:start --server=frankenphp --host=0.0.0.0 --port=8888
