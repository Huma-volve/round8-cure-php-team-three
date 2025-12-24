<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Specialization;
use Spatie\Permission\Traits\HasRoles;
class Doctor extends Model
{
    use HasFactory,HasRoles;

    protected $fillable = [
        'user_id',
        // 'name',
        // 'email',
        // 'password',
        'specializations_id',
        // 'mobile_number',
        'license_number',
        'session_price',
        'availability_slots',
        'clinic_location'
    ];

    protected $casts = [
        'session_price' => 'decimal:2',
        'availability_slots' => 'array',
        'clinic_location' => 'array'
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'doctor_id');
    }

    public function specialization()
    {
        return $this->belongsTo(Specialization::class, 'specializations_id');
    }

    public function averageRating()
    {
        return $this->reviews()->avg('rating');
    }

    public function reviewsCount()
    {
        return $this->reviews()->count();
    }

    public function user()
    {
        return $this->belongsTo(User::class , 'user_id');
    }
   
}
