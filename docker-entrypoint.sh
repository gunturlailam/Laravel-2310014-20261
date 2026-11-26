#!/usr/bin/env bash
set -e

PORT="${PORT:-8080}"

# Configure Apache to listen on dynamic platform PORT (Render/Koyeb/HF Spaces)
sed -ri "s/Listen 80/Listen ${PORT}/g" /etc/apache2/ports.conf
sed -ri "s/<VirtualHost \*:80>/<VirtualHost *:${PORT}>/g" /etc/apache2/sites-available/000-default.conf

# Ensure storage directories & public symlink exist
mkdir -p /var/www/html/storage/app/public/profiles
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
php artisan storage:link --force || true

# Run database migrations automatically
php artisan migrate --force || true

# Cache routes & config for production
php artisan config:cache || true
php artisan route:cache || true

exec apache2-foreground

