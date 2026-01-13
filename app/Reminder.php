<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'reminder_date',
        'note',
        'sent',
        'sent_at',
    ];

    protected $casts = [
        'reminder_date' => 'datetime',
        'sent' => 'boolean',
        'sent_at' => 'datetime',
    ];

    /**
     * Get the booking for this reminder.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the user (customer) for this reminder.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
