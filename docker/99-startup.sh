#!/bin/sh
set -e

echo "==> [Startup] Initializing application..."

# 1. Clear any stale cached configurations from Docker build
rm -f /var/www/html/bootstrap/cache/*.php
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# 2. Run migrations on connected external database if configured
if [ -n "$DATABASE_URL" ] || [ "$DB_CONNECTION" = "pgsql" ] || [ "$DB_CONNECTION" = "mysql" ]; then
    echo "==> [Startup] Running database migrations on external database..."
    php artisan migrate --force || true
    echo "==> [Startup] Ensuring base seeders are populated..."
    php artisan db:seed --force || true
fi

# 3. Cache configuration and views with runtime live environment variables
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 4. Ensure storage symlink exists
php artisan storage:link || true

echo "==> [Startup] Initialization completed successfully."
