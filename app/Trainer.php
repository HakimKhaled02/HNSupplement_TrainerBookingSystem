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
        'rating',
        'status',
        'availability',
    ];

    protected $casts = [
        'availability' => 'array',
        'rating' => 'decimal:2',
    ];

    /**
     * Get the user that owns the trainer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
