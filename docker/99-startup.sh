#!/bin/sh
set -e

echo "==> [Startup] Initializing application..."

# 1. Clear any stale cached configurations from Docker build
rm -f /var/www/html/bootstrap/cache/*.php

# 2. Write runtime environment variables into .env so all PHP processes reliably see them
if [ -n "$DATABASE_URL" ]; then
    echo "==> [Startup] Writing live DATABASE_URL to .env..."
    sed -i '/^DATABASE_URL=/d' /var/www/html/.env 2>/dev/null || true
    echo "DATABASE_URL=\"$DATABASE_URL\"" >> /var/www/html/.env
fi

if [ -n "$DB_CONNECTION" ]; then
    echo "==> [Startup] Writing DB_CONNECTION=$DB_CONNECTION to .env..."
    sed -i '/^DB_CONNECTION=/d' /var/www/html/.env 2>/dev/null || true
    echo "DB_CONNECTION=\"$DB_CONNECTION\"" >> /var/www/html/.env
fi

# 3. Clear artisan caches
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# 4. Run migrations on connected external database if configured
if [ -n "$DATABASE_URL" ] || [ "$DB_CONNECTION" = "pgsql" ] || [ "$DB_CONNECTION" = "mysql" ]; then
    echo "==> [Startup] Running database migrations on external database..."
    php artisan migrate --force || true
    echo "==> [Startup] Ensuring base seeders are populated..."
    php artisan db:seed --force || true
fi

# 5. Cache configuration and views with runtime live environment variables
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Ensure storage symlink exists cleanly
rm -rf /var/www/html/public/storage
php artisan storage:link || true

echo "==> [Startup] Initialization completed successfully."
