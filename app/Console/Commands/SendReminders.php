<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Reminder;
use App\Mail\ReminderNotification;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reminder notifications for upcoming bookings';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        $endTime = $now->copy()->addMinutes(5); // Send reminders within next 5 minutes
        
        // Get reminders that should be sent now (within next 5 minutes) and haven't been sent yet
        $reminders = Reminder::where('sent', false)
            ->whereBetween('reminder_date', [$now, $endTime])
            ->with(['user', 'booking.trainer.user'])
            ->get();
        
        $sentCount = 0;
        
        foreach ($reminders as $reminder) {
            try {
                // Send email notification
                Mail::to($reminder->user->email)->send(new ReminderNotification($reminder));
                
                // Mark as sent
                $reminder->update([
                    'sent' => true,
                    'sent_at' => Carbon::now(),
                ]);
                
                $sentCount++;
                
                $this->info("Reminder sent to {$reminder->user->email} for booking #{$reminder->booking_id}");
            } catch (\Exception $e) {
                $this->error("Failed to send reminder #{$reminder->id}: " . $e->getMessage());
            }
        }
        
        if ($sentCount > 0) {
            $this->info("Successfully sent {$sentCount} reminder(s).");
        } else {
            $this->info("No reminders to send at this time.");
        }
        
        return 0;
    }
}
