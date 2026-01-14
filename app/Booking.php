<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainer_id',
        'user_id',
        'start_date',
        'end_date',
        'selected_days',
        'time_slots',
        'attendance',
        'total_amount',
        'payment_status',
        'payment_expires_at',
        'status',
        'progress',
        'refund_request_status',
        'refund_reason',
        'refund_details',
        'refund_requested_at',
        'refund_processed_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'selected_days' => 'array',
        'time_slots' => 'array',
        'attendance' => 'array',
        'total_amount' => 'decimal:2',
        'payment_expires_at' => 'datetime',
        'refund_requested_at' => 'datetime',
        'refund_processed_at' => 'datetime',
    ];

    /**
     * Get the trainer for this booking.
     */
    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    /**
     * Get the user (customer) for this booking.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the review for this booking.
     */
    public function review()
    {
        return $this->hasOne(Review::class);
    }

    /**
     * Get all reminders for this booking.
     */
    public function reminders()
    {
        return $this->hasMany(Reminder::class);
    }

    /**
     * Calculate and update progress based on attendance.
     */
    public function calculateProgress()
    {
        if (!$this->time_slots || empty($this->time_slots)) {
            return 'upcoming';
        }

        $attendance = $this->attendance ?? [];
        
        // Count total expected time slots and marked slots
        $startDate = \Carbon\Carbon::parse($this->start_date);
        $endDate = \Carbon\Carbon::parse($this->end_date);
        $currentDate = $startDate->copy();
        
        $totalSlots = 0;
        $markedSlots = 0;
        
        // Group time slots by day
        $slotsByDay = [];
        foreach ($this->time_slots as $slot) {
            $day = $slot['day'] ?? null;
            if ($day) {
                if (!isset($slotsByDay[$day])) {
                    $slotsByDay[$day] = [];
                }
                $slotsByDay[$day][] = $slot;
            }
        }
        
        // Create a set of all expected slot keys
        $expectedSlots = [];
        while ($currentDate <= $endDate) {
            $dayOfWeek = strtolower($currentDate->format('l'));
            $dateStr = $currentDate->format('Y-m-d');
            
            if (isset($slotsByDay[$dayOfWeek])) {
                foreach ($slotsByDay[$dayOfWeek] as $slot) {
                    $startTime = $slot['start_time'] ?? '';
                    $slotKey = $dayOfWeek . '_' . $startTime . '_' . $dateStr;
                    $expectedSlots[$slotKey] = true;
                    $totalSlots++;
                }
            }
            $currentDate->addDay();
        }
        
        if ($totalSlots === 0) {
            return 'upcoming';
        }
        
        // Count marked slots
        foreach ($attendance as $att) {
            $day = $att['day'] ?? '';
            $startTime = $att['start_time'] ?? '';
            $date = $att['date'] ?? '';
            $slotKey = $day . '_' . $startTime . '_' . $date;
            
            if (isset($expectedSlots[$slotKey])) {
                $markedSlots++;
            }
        }
        
        if ($markedSlots === 0) {
            return 'upcoming';
        }
        
        if ($markedSlots >= $totalSlots) {
            return 'completed';
        }
        
        return 'ongoing';
    }

    /**
     * Update progress automatically.
     */
    public function updateProgress()
    {
        $this->progress = $this->calculateProgress();
        $this->save();
    }
}
