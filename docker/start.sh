#!/bin/sh

# Use PORT from Render environment, default to 10000
if [ -n "$PORT" ]; then
    sed -i "s/listen 10000/listen $PORT/" /etc/nginx/http.d/default.conf
fi

# Run migrations on deploy
php artisan migrate --force 2>/dev/null || true

# Clear and rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start supervisor (manages nginx + php-fpm)
exec /usr/bin/supervisord -c /etc/supervisord.conf
