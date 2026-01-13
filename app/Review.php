<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'trainer_id',
        'user_id',
        'rating',
        'feedback',
    ];

    /**
     * Get the booking for this review.
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Get the trainer for this review.
     */
    public function trainer()
    {
        return $this->belongsTo(Trainer::class);
    }

    /**
     * Get the user (customer) who wrote this review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
