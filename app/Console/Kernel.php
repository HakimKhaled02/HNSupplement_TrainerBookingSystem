<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Cancel expired pending bookings every minute
        $schedule->call(function () {
            \App\Booking::where('payment_status', 'pending')
                ->whereNotNull('payment_expires_at')
                ->where('payment_expires_at', '<=', now())
                ->update([
                    'payment_status' => 'cancelled',
                    'status' => 'cancelled'
                ]);
        })->everyMinute();

        // Send reminder notifications every minute
        $schedule->command('reminders:send')->everyMinute();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}

