#!/bin/bash
set -e

# Fix permissions only if needed (faster)
if [ ! -w /var/www/html/storage ]; then
    echo "Fixing storage permissions..."
    chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
    chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
fi

# Continue with Sail's default entrypoint
exec /usr/bin/start-container
