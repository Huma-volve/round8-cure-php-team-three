<?php

namespace App\Models;

use App\Enums\BookingStatus;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['user_id','doctor_id','booking_date','booking_time','status','payment_method_id', 'price'];

    protected $cast = [
        'status' => BookingStatus::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

     public function review()
    {
        return $this->hasOne(Review::class, 'booking_id');
    }

    public function isCompleted()
    {
        return $this->status === 'Completed';
    }
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
