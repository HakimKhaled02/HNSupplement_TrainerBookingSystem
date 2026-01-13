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

    /**
     * Get all reviews for this trainer.
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Calculate and update average rating from reviews.
     */
    public function updateRating()
    {
        $reviews = $this->reviews;
        if ($reviews->count() > 0) {
            $averageRating = $reviews->avg('rating');
            $this->rating = round($averageRating, 2);
        } else {
            $this->rating = 0;
        }
        $this->save();
    }

    /**
     * Calculate distance from given coordinates using Haversine formula.
     * Returns distance in kilometers.
     *
     * @param float $latitude
     * @param float $longitude
     * @return float|null Distance in kilometers, or null if trainer has no coordinates
     */
    public function calculateDistance($latitude, $longitude)
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        $earthRadius = 6371; // Earth's radius in kilometers

        $latFrom = deg2rad($this->latitude);
        $lonFrom = deg2rad($this->longitude);
        $latTo = deg2rad($latitude);
        $lonTo = deg2rad($longitude);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = sin($latDelta / 2) * sin($latDelta / 2) +
             cos($latFrom) * cos($latTo) *
             sin($lonDelta / 2) * sin($lonDelta / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return round($distance, 2);
    }
}
