# Environment Variables for Render Deployment

Copy and paste these environment variables into your Render service settings:

## Required Environment Variables

### Application Settings
```
APP_NAME=AFIA-ORBIT
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:GENERATE_THIS_IN_RENDER
APP_URL=https://your-service-name.onrender.com
```

### Localization
```
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US
```

### Security
```
BCRYPT_ROUNDS=12
```

### Logging
```
LOG_CHANNEL=stderr
LOG_LEVEL=info
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
```

### Database (SQLite)
```
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite
```

### Session Management
```
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
```

### Cache & Queue
```
CACHE_STORE=database
QUEUE_CONNECTION=database
BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
```

### Mail Configuration
```
MAIL_MAILER=log
MAIL_FROM_ADDRESS=hello@afia-orbit.com
MAIL_FROM_NAME=AFIA-ORBIT
```

### Frontend
```
VITE_APP_NAME=AFIA-ORBIT
```

## Step-by-Step Instructions for Render:

### 1. In Render Dashboard:
- Go to your service settings
- Click on "Environment" tab
- Add each variable above as Key-Value pairs

### 2. Special Instructions:

**APP_KEY**: 
- Leave this blank initially
- Render will auto-generate it, OR
- Generate one locally with: `php artisan key:generate --show`
- Copy the output (including "base64:" prefix)

**APP_URL**: 
- Replace "your-service-name" with your actual Render service name
- Example: `https://afia-orbit-xyz.onrender.com`

### 3. Important Notes:
- Don't include quotes around values in Render
- Make sure APP_DEBUG is set to `false` for production
- The database path must be exactly: `/var/www/html/database/database.sqlite`

### 4. After Setting Variables:
- Click "Save Changes"
- Render will automatically redeploy your service
- Monitor the deployment logs for any issues
