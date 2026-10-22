#!/bin/sh
set -e

echo "==> Starting application initialization..."

# If external database is configured, run migrations on boot
if [ -n "$DATABASE_URL" ] || [ "$DB_CONNECTION" = "pgsql" ] || [ "$DB_CONNECTION" = "mysql" ]; then
    echo "==> Running database migrations on connected external database..."
    php artisan migrate --force || true
fi

# Re-cache configuration, routes, and views with live environment variables
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "==> Application initialization completed successfully."
