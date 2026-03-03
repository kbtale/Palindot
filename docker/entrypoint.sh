#!/bin/bash

# Run composer dump-autoload
composer dump-autoload

# Wait for database to be ready
echo "Waiting for database..."
until php -r "try { new PDO('mysql:host=db;dbname=palindot', 'root', 'root'); exit(0); } catch (Exception \$e) { exit(1); }"; do
  sleep 1
done
echo "Database is ready!"

# Run migrations
php artisan migrate --force

# Generate key if not set
if [ -z "$APP_KEY" ]; then
  php artisan key:generate
fi

# Start PHP-FPM in background
php-fpm -D

# Start Nginx in foreground
nginx -g "daemon off;"
