#!/bin/bash
set -e

# Use PORT env var (Render sets this, default to 10000)
APP_PORT=${PORT:-10000}

# Configure Apache to listen on the correct port
sed -i "s/Listen 8080/Listen ${APP_PORT}/" /etc/apache2/ports.conf
sed -i "s/\*:8080/*:${APP_PORT}/" /etc/apache2/sites-available/000-default.conf

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Create SQLite database if using SQLite
if [ "$DB_CONNECTION" = "sqlite" ]; then
    touch /var/www/html/database/database.sqlite
    chmod 777 /var/www/html/database/database.sqlite
    chmod 777 /var/www/html/database/
    chown -R www-data:www-data /var/www/html/database/
    chown www-data:www-data /var/www/html/database/database.sqlite
fi

# Run migrations
php artisan migrate --force || echo "Migrations will run after database is ready"

# Clear cached config (installer will write .env later)
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Create storage link
php artisan storage:link || true

# Set proper permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Starting Apache on port ${APP_PORT}..."

# Start Apache
exec apache2-foreground
