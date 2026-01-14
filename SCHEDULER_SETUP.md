# Laravel Scheduler Setup Guide

## For Windows Development

### Option 1: Manual Testing (Recommended for Development)

You can test the scheduler manually by running:

```bash
php artisan schedule:run
```

Or run it continuously in a separate terminal window:

```bash
php artisan schedule:work
```

This will run the scheduler every minute automatically. Press `Ctrl+C` to stop.

### Option 2: Windows Task Scheduler (For Production/Always Running)

1. **Open Task Scheduler**:
   - Press `Win + R`, type `taskschd.msc`, and press Enter

2. **Create Basic Task**:
   - Click "Create Basic Task" in the right panel
   - Name: "Laravel Scheduler"
   - Description: "Runs Laravel scheduled tasks every minute"

3. **Set Trigger**:
   - Trigger: "When the computer starts"
   - Or: "Daily" and set to repeat every 1 minute

4. **Set Action**:
   - Action: "Start a program"
   - Program/script: `C:\path\to\php.exe` (find your PHP path)
   - Add arguments: `artisan schedule:run`
   - Start in: `D:\hnsupplement` (your project path)

5. **Alternative: Use Batch File**:
   Create a file `run-scheduler.bat` in your project root:
   ```batch
   @echo off
   cd /d D:\hnsupplement
   php artisan schedule:run
   ```
   Then point Task Scheduler to this batch file.

### Option 3: Use Laravel Schedule Work Command (Easiest for Development)

Laravel 8+ includes a `schedule:work` command that runs the scheduler continuously:

```bash
php artisan schedule:work
```

This is perfect for local development. It runs in the foreground and checks for scheduled tasks every minute.

### Option 4: Use a Windows Service

You can use tools like NSSM (Non-Sucking Service Manager) to run the scheduler as a Windows service.

## Testing Your Reminders

1. **Set a test reminder** with a date/time 1-2 minutes in the future
2. **Run the scheduler**:
   ```bash
   php artisan schedule:run
   ```
3. **Check your email** (make sure mail is configured in `.env`)

## Mail Configuration

Make sure your `.env` file has mail settings configured:


```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Quick Start (Recommended for Now)

For immediate testing, open a new terminal/command prompt and run:

```bash
cd D:\hnsupplement
php artisan schedule:work
```

Keep this terminal open. The scheduler will run automatically every minute and send reminders when they're due.

