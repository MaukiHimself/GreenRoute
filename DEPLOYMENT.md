# AFIA ORBIT - Deployment Guide

## 🚀 Recommended Deployment Platforms

### 1. Railway (Recommended)

Railway is the best choice for Laravel applications with built-in PHP support.

#### Steps:
1. **Sign up** at [railway.app](https://railway.app)
2. **Connect GitHub** repository
3. **Add PostgreSQL** database service
4. **Set environment variables**:
   ```
   APP_NAME=AFIA ORBIT
   APP_ENV=production
   APP_KEY=base64:your-generated-key
   APP_DEBUG=false
   APP_URL=https://your-app.railway.app
   
   DB_CONNECTION=pgsql
   DB_HOST=your-db-host
   DB_PORT=5432
   DB_DATABASE=railway
   DB_USERNAME=postgres
   DB_PASSWORD=your-db-password
   ```
5. **Deploy** automatically

### 2. Heroku

#### Steps:
1. **Install Heroku CLI**
2. **Login**: `heroku login`
3. **Create app**: `heroku create your-app-name`
4. **Add PostgreSQL**: `heroku addons:create heroku-postgresql:hobby-dev`
5. **Set environment variables**:
   ```bash
   heroku config:set APP_NAME="AFIA ORBIT"
   heroku config:set APP_ENV=production
   heroku config:set APP_KEY=your-generated-key
   heroku config:set APP_DEBUG=false
   ```
6. **Deploy**: `git push heroku main`

### 3. DigitalOcean App Platform

#### Steps:
1. **Create app** in DigitalOcean dashboard
2. **Connect GitHub** repository
3. **Configure build settings**:
   - Build command: `composer install --no-dev --optimize-autoloader`
   - Run command: `php artisan serve --host=0.0.0.0 --port=8080`
4. **Add database** service
5. **Set environment variables**
6. **Deploy**

## 🔧 Pre-deployment Setup

### 1. Generate Application Key
```bash
php artisan key:generate --show
```

### 2. Optimize for Production
```bash
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 3. Database Migration
```bash
php artisan migrate --force
```

## 📁 Required Files

- `vercel.json` - For Vercel (not recommended)
- `railway.json` - For Railway
- `Procfile` - For Heroku
- `.env.production` - Environment variables template

## ⚠️ Important Notes

1. **Vercel is NOT recommended** for Laravel applications
2. **Use Railway or Heroku** for best results
3. **Always use PostgreSQL** in production
4. **Set APP_DEBUG=false** in production
5. **Use HTTPS** for all production URLs

## 🎯 Next Steps

1. Choose a platform (Railway recommended)
2. Follow the platform-specific steps
3. Set up your database
4. Configure environment variables
5. Deploy and test

## 📞 Support

If you need help with deployment, refer to:
- [Railway Documentation](https://docs.railway.app)
- [Heroku PHP Documentation](https://devcenter.heroku.com/categories/php-support)
- [Laravel Deployment Guide](https://laravel.com/docs/deployment)
