# Environment Variables Reference for Railway

Copy these variables to your Railway project's Variables tab. Replace placeholder values with your actual values.

## Required Variables for Railway

### Application Settings

```
APP_NAME=HN Supplement
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.railway.app
APP_TIMEZONE=UTC
```

**Important:** Replace `your-app-name.railway.app` with your actual Railway URL after first deployment.

### Application Key

```
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
```

**How to generate:** After first deployment, run `php artisan key:generate --show` in Railway shell and copy the output.

### Database Configuration

Use Railway's reference variables to automatically connect to your MySQL service:

```
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQL_HOST}}
DB_PORT=${{MySQL.MYSQL_PORT}}
DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
DB_USERNAME=${{MySQL.MYSQL_USER}}
DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}
```

**Note:** Replace `MySQL` with your actual MySQL service name if different.

### Session & Cache

```
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_DRIVER=database
QUEUE_CONNECTION=database
```

### Logging

```
LOG_CHANNEL=stack
LOG_LEVEL=error
```

### Mail Configuration (If using email features)

```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME=HN Supplement
```

**Note:** For Gmail, generate an "App Password" in Google Account settings.

## Quick Setup Checklist

- [ ] Add all required application variables
- [ ] Add database variables using Railway reference syntax
- [ ] Generate and add APP_KEY
- [ ] Set APP_URL to your Railway domain
- [ ] Add mail variables if using email features
- [ ] Deploy and test

