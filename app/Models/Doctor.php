<?php

namespace App\Models;

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

  protected $casts = [
        'availability_slots' => 'array',
        'clinic_location' => 'array',
    ];
}
