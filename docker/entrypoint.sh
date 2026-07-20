#!/bin/bash
set -e

# Use PORT env var (Render sets this, default to 10000)
APP_PORT=${PORT:-10000}

# Configure Apache to listen on the correct port
sed -i "s/Listen 8080/Listen ${APP_PORT}/" /etc/apache2/ports.conf
sed -i "s/\*:8080/*:${APP_PORT}/" /etc/apache2/sites-available/000-default.conf

# Create .env file from environment variables (skip installer)
if [ ! -f /var/www/html/.env ] || [ ! -s /var/www/html/.env ]; then
    cat > /var/www/html/.env << EOF
APP_NAME="${APP_NAME:-Pharmacy Management System}"
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY:-}
APP_DEBUG=${APP_DEBUG:-true}
APP_URL=${APP_URL:-https://pharmacy-management-system-34g5.onrender.com}
LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug
DB_CONNECTION=${DB_CONNECTION:-sqlite}
DB_DATABASE=${DB_DATABASE:-/var/www/html/database/database.sqlite}
BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="\${APP_NAME}"
VITE_APP_NAME="\${APP_NAME}"
EOF
fi

# Generate app key if not set
if ! grep -q "APP_KEY=base64:" /var/www/html/.env 2>/dev/null; then
    php artisan key:generate --force
fi

# Create SQLite database if using SQLite
if [ "$DB_CONNECTION" = "sqlite" ]; then
    touch /var/www/html/database/database.sqlite
    chmod 777 /var/www/html/database/database.sqlite
    chmod 777 /var/www/html/database/
    chown -R www-data:www-data /var/www/html/database/
fi

# Create storage link
php artisan storage:link || true

# Mark app as installed (skip Laravel Installer)
touch /var/www/html/storage/installed

# Run migrations and seed
php artisan migrate --force || true
php artisan db:seed --force || true

# Cache config
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Set proper permissions
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

echo "Starting Apache on port ${APP_PORT}..."

# Start Apache
exec apache2-foreground
