#!/bin/bash
set -e

echo "🔧 Fixing Laravel permissions..."

# PHP runs as the "sail" user (UID/GID = WWWUSER/WWWGROUP, set by start-container),
# so give it the writable folders. Under WSL this also keeps them owned by you.
APP_UID="${WWWUSER:-1000}"
APP_GID="${WWWGROUP:-1000}"

# Ensure framework directories and the log file exist
mkdir -p /var/www/html/storage/framework/{sessions,views,cache} \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache
touch /var/www/html/storage/logs/laravel.log

# On a Windows drive (J:\...) ownership isn't enforced; a refused chown is harmless there
chown -R "$APP_UID:$APP_GID" /var/www/html/storage /var/www/html/bootstrap/cache || true
chmod -R ug+rwX /var/www/html/storage /var/www/html/bootstrap/cache || true

echo "✅ Permissions fixed successfully!"

# Execute the original entrypoint
exec /usr/local/bin/start-container "$@"
