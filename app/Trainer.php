<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'phone',
        'state',
        'area',
        'category',
        'profile_picture',
        'qualification_file',
        'latitude',
        'longitude',
        'bio',
        'specialties',
        'certifications',
        'salary',
        'rating',
        'status',
        'availability',
    ];

    protected $casts = [
        'specialties' => 'array',
        'certifications' => 'array',
        'availability' => 'array',
        'salary' => 'decimal:2',
        'rating' => 'decimal:2',
    ];

    /**
     * Get the user that owns the trainer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all bookings for this trainer.
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
