#!/bin/bash

# AFIA ORBIT - Deployment Script
# This script prepares your Laravel application for deployment

echo "🚀 AFIA ORBIT Deployment Preparation"
echo "====================================="

# Check if we're in a Laravel project
if [ ! -f "artisan" ]; then
    echo "❌ Error: This doesn't appear to be a Laravel project"
    exit 1
fi

echo "📦 Installing production dependencies..."
composer install --no-dev --optimize-autoloader

echo "🔑 Generating application key..."
php artisan key:generate --force

echo "📊 Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🗄️ Running database migrations..."
php artisan migrate --force

echo "📁 Building assets..."
npm run build

echo "✅ Deployment preparation complete!"
echo ""
echo "🎯 Next steps:"
echo "1. Choose a deployment platform (Railway recommended)"
echo "2. Set up your database"
echo "3. Configure environment variables"
echo "4. Deploy your application"
echo ""
echo "📖 See DEPLOYMENT.md for detailed instructions"
