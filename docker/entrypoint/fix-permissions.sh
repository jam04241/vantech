#!/bin/bash
set -e

echo "🔧 Fixing Laravel permissions..."

# Fix ownership and permissions for storage
chown -R www-data:www-data /var/www/html/storage
chmod -R 775 /var/www/html/storage

# Fix ownership and permissions for bootstrap/cache
chown -R www-data:www-data /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/bootstrap/cache

# Ensure log file exists and is writable
touch /var/www/html/storage/logs/laravel.log
chown www-data:www-data /var/www/html/storage/logs/laravel.log
chmod 666 /var/www/html/storage/logs/laravel.log

# Fix framework directories
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
chown -R www-data:www-data /var/www/html/storage/framework
chmod -R 775 /var/www/html/storage/framework

echo "✅ Permissions fixed successfully!"

# Execute the original entrypoint
exec /usr/local/bin/start-container "$@"
