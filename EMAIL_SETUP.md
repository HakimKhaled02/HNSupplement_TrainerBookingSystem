# Email Configuration Guide

## Email Setup for Trainer Notifications

To send emails to trainers when their applications are approved or rejected, you need to configure email settings in Laravel.

### Option 1: Using Mailtrap (Recommended for Development/Testing)

1. Sign up for a free account at https://mailtrap.io
2. Create an inbox and get your credentials
3. Add these to your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@hnsupplement.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Option 2: Using Gmail SMTP

1. Enable 2-Step Verification on your Gmail account
2. Generate an App Password: https://myaccount.google.com/apppasswords
3. Add these to your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your_email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

### Option 3: Using Other SMTP Providers

For other providers (SendGrid, Mailgun, etc.), update your `.env` with their SMTP settings:

```env
MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

### After Configuration

1. Update your `.env` file with the email settings
2. Clear config cache: `php artisan config:clear`
3. Test the email functionality by approving/rejecting a trainer

### Testing

You can test emails using:
- Mailtrap (catches all emails in development)
- Laravel Tinker: `php artisan tinker` then `Mail::raw('Test', function($msg) { $msg->to('test@example.com')->subject('Test'); });`

