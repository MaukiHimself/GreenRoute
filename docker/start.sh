#!/bin/bash

# Generate application key if not set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Ensure storage directories exist with proper permissions
mkdir -p storage/app/public/certificates
mkdir -p storage/framework/{sessions,views,cache}
mkdir -p storage/logs
chmod -R 775 storage
chmod -R 775 bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Clear and cache configuration
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
php artisan migrate --force

# Clear any stale sessions from previous deploys
php artisan db:seed --class=ClearSessionsSeeder --force 2>/dev/null || true

# Create storage link (force recreate if exists)
php artisan storage:link --force || true

# Start Apache in foreground
apache2-foreground
