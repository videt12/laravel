FROM serversideup/php:8.3-fpm-nginx

ENV AUTORUN_ENABLED=true \
    SSL_MODE=off \
    PHP_OPCACHE_ENABLE=1

WORKDIR /var/www/html

USER root

# Copy application files (including pre-bundled public/build)
COPY --chown=www-data:www-data . /var/www/html

USER www-data

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Setup Laravel environment, SQLite db, run migrations & seeders, then build cache
RUN cp .env.example .env \
    && php artisan key:generate \
    && mkdir -p storage/framework/{sessions,views,cache/data} \
    && mkdir -p database \
    && touch database/database.sqlite \
    && php artisan migrate --force \
    && php artisan db:seed --force \
    && rm -rf public/storage \
    && php artisan storage:link \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache

USER root

# Ensure correct permissions on database, storage, and cache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database \
    && chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

USER www-data

EXPOSE 8080
