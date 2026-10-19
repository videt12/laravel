FROM serversideup/php:8.3-fpm-nginx

# Environment variables
ENV AUTORUN_ENABLED=true \
    SSL_MODE=off \
    PHP_OPCACHE_ENABLE=1

WORKDIR /var/www/html

USER root

# Copy application files (including pre-bundled public/build)
COPY --chown=www-data:www-data . /var/www/html

# Setup storage and database directories
RUN mkdir -p storage/framework/{sessions,views,cache/data} \
    && mkdir -p database \
    && touch database/database.sqlite \
    && chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

USER www-data

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Setup Laravel environment, run migrations, seeders and caches
RUN cp .env.example .env \
    && php artisan key:generate \
    && php artisan migrate --force \
    && php artisan db:seed --force \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

USER root

EXPOSE 8080
