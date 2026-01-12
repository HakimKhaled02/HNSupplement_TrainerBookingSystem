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
        'total_amount',
        'payment_status',
        'payment_expires_at',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'selected_days' => 'array',
        'time_slots' => 'array',
        'total_amount' => 'decimal:2',
        'payment_expires_at' => 'datetime',
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
}
