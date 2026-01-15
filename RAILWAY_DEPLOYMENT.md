# Railway Deployment Guide for HN Supplement

This guide will help you deploy your Laravel application to Railway step by step.

## Prerequisites

1. A GitHub/GitLab/Bitbucket account with your code pushed
2. A Railway account (sign up at https://railway.app)

## Step 1: Push Your Code to GitHub

If you haven't already, push your code to a Git repository:

```bash
git add .
git commit -m "Prepare for Railway deployment"
git push origin main
```

## Step 2: Create Railway Project

1. Go to https://railway.app and sign in
2. Click **"New Project"**
3. Select **"Deploy from GitHub repo"**
4. Authorize Railway to access your GitHub account if prompted
5. Select your repository (`hnsupplement`)
6. Railway will automatically detect it's a Laravel app and start building

## Step 3: Add MySQL Database

1. In your Railway project dashboard, click **"+ New"**
2. Select **"Database"** → **"Add MySQL"**
3. Railway will create a MySQL database for you
4. Wait for it to finish provisioning (takes 1-2 minutes)

## Step 4: Configure Environment Variables

1. In your Railway project, you'll see your web service and MySQL service
2. Click on your **web service** (the one with your app name)
3. Go to the **"Variables"** tab
4. Add the following environment variables:

### Required Application Variables

```
APP_NAME=HN Supplement
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app-name.railway.app
```

**Note:** Replace `your-app-name.railway.app` with your actual Railway URL (you'll get this after first deployment)

### Database Variables (Use Railway's Reference Syntax)

Instead of hardcoding database credentials, use Railway's reference variables that automatically connect to your MySQL service:

```
DB_CONNECTION=mysql
DB_HOST=${{MySQL.MYSQL_HOST}}
DB_PORT=${{MySQL.MYSQL_PORT}}
DB_DATABASE=${{MySQL.MYSQL_DATABASE}}
DB_USERNAME=${{MySQL.MYSQL_USER}}
DB_PASSWORD=${{MySQL.MYSQL_PASSWORD}}
```

**Important:** Replace `MySQL` with your actual MySQL service name if it's different. You can find the service name in your Railway dashboard.

### Session & Cache Variables

```
SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_DRIVER=database
QUEUE_CONNECTION=database
```

### Logging Variables

```
LOG_CHANNEL=stack
LOG_LEVEL=error
```

### Timezone

```
APP_TIMEZONE=UTC
```

### Mail Configuration (Optional - if you use email features)

If your app sends emails (like the reminder notifications), configure mail:

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

**Note:** For Gmail, you'll need to generate an "App Password" in your Google Account settings.

### Generate APP_KEY

After your first deployment, you need to generate an `APP_KEY`:

1. Go to your web service → **"Deployments"** → Click on the latest deployment
2. Click **"View Logs"** or use the **"Shell"** option
3. Run this command:
   ```bash
   php artisan key:generate --show
   ```
4. Copy the generated key (it will look like `base64:...`)
5. Go back to **Variables** and add:
   ```
   APP_KEY=base64:your-generated-key-here
   ```
6. Redeploy your service

**OR** the `railway.json` file is already configured to auto-generate the key on each deployment, so you might not need to do this manually.

## Step 5: Deploy

1. Railway will automatically deploy when you push to your main branch
2. Or you can manually trigger a deployment from the Railway dashboard
3. Wait for the build to complete (usually 2-5 minutes)
4. Check the deployment logs for any errors

## Step 6: Run Migrations

After your first successful deployment:

1. Go to your web service → **"Deployments"** → Latest deployment
2. Click **"Shell"** or use Railway CLI
3. Run:
   ```bash
   php artisan migrate --force
   ```

**Note:** The `railway.json` file is configured to run migrations automatically, but you can also do it manually.

## Step 7: Set Up Storage Link

If your app uses file uploads:

1. In the Railway shell, run:
   ```bash
   php artisan storage:link
   ```

**Note:** This is also included in the automatic deployment command.

## Step 8: Get Your App URL

1. Go to your web service → **"Settings"** tab
2. Scroll down to **"Domains"**
3. Railway provides a default domain like: `your-app-name.up.railway.app`
4. Copy this URL - this is your `APP_URL`

Update your `APP_URL` variable with this URL and redeploy.

## Step 9: Test Your Deployment

1. Visit your Railway URL
2. Check if the homepage loads
3. Test your application features
4. Check the logs if something doesn't work

## Troubleshooting

### Build Fails - "No version available for php 8.1"

**This error means Railway can't find PHP 8.1.** The solution:

1. **The project is already configured for PHP 8.2** - We've updated `composer.json` and added `nixpacks.toml` and `.php-version` files
2. **Force Railway to use Nixpacks instead of Railpack:**
   - Go to your service → **Settings** tab
   - Scroll to **"Build"** section
   - Set **"Build Command"** to: `echo "Using Nixpacks"`
   - Or in Railway dashboard, go to your service → **Settings** → **Build** → Change builder to **"Nixpacks"** (if available)
3. **Redeploy** - Push your code again or trigger a new deployment

### Build Fails - General

- Check the build logs in Railway
- Ensure `composer.json` is correct
- Verify PHP version compatibility (your app requires PHP 8.2+)
- Make sure `nixpacks.toml` and `.php-version` files are in your repository

### Database Connection Errors

- Verify database variables are set correctly
- Make sure you're using the reference syntax: `${{ServiceName.VARIABLE}}`
- Check that your MySQL service is running

### 500 Internal Server Error

- Check the deployment logs
- Ensure `APP_KEY` is set
- Run migrations: `php artisan migrate --force`
- Clear cache: `php artisan config:clear`

### Can't See Images/Uploads

- Railway's filesystem is ephemeral (files are lost on redeploy)
- Consider using external storage like AWS S3 for file uploads
- For now, make sure `storage:link` is run

### APP_KEY Error

- The `railway.json` includes `php artisan key:generate --force` which should handle this
- If you still see errors, manually generate and set the key as described in Step 4

## Important Notes

1. **File Storage**: Railway's filesystem is ephemeral. Any files uploaded to `storage/app` will be lost on redeployment. Consider using cloud storage (S3, etc.) for production.

2. **Database Backups**: Railway provides automatic backups for paid plans. Consider setting up regular backups.

3. **Environment Variables**: Never commit your `.env` file. Railway handles environment variables through their dashboard.

4. **Scheduler**: If you use Laravel's task scheduler, you'll need to set up a separate worker process or use Railway's cron job feature.

## Next Steps

- Set up a custom domain (optional)
- Configure SSL (automatic with Railway)
- Set up monitoring and alerts
- Configure backups

## Need Help?

- Railway Docs: https://docs.railway.app
- Railway Discord: https://discord.gg/railway
- Laravel Docs: https://laravel.com/docs

