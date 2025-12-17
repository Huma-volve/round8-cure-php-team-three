<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'doctor_id',
        'rating',
        'comment'
    ];

    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];


    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }


    public function setRatingAttribute($value)
    {
        if ($value < 1 || $value > 5) {
            throw new \InvalidArgumentException('Rating must be between 1 and 5');
        }
        $this->attributes['rating'] = $value;
    }
}
