<?php

namespace App\Models;

use App\Models\User;
use App\Models\Booking;


use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
      use HasFactory;
 protected $guarded = [];

 public function bookings()
 {
    return $this->hasMany(Booking::class);
 }

 public function user()
    {
        return $this->belongsTo(User::class);
    }


  protected $casts = [
        'availability_slots' => 'array',
        'clinic_location' => 'array',
    ];
}
