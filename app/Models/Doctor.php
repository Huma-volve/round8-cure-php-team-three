<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
 protected $guarded = [];

 public function bookings()
 {
    return $this->hasMany(Booking::class);
 }
  public function favorites(): MorphMany
    {
        return $this->morphMany(Favorite::class, 'favorable');
    }

    public function isFavoritedBy($userId)
    {
        return $this->favorites()->where('user_id', $userId)->exists();
    }
    public function specializations()
    {
        return $this->belongsTo(Specialization::class);
    }
}
