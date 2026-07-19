#!/bin/bash
set -e

# Use PORT env var (Render sets this, default to 10000)
APP_PORT=${PORT:-10000}

# Configure Apache to listen on the correct port
sed -i "s/Listen .*/Listen ${APP_PORT}/" /etc/apache2/ports.conf
sed -i "s/<VirtualHost \*:.*/<VirtualHost *:${APP_PORT}>/" /etc/apache2/sites-available/000-default.conf

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Run migrations
php artisan migrate --force

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create storage link
php artisan storage:link || true

# Set proper permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Starting Apache on port ${APP_PORT}..."

# Start Apache
exec apache2-foreground
