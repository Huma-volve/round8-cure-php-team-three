<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
     protected $fillable = [
        'booking_id',
        'payment_method_id',
        'amount',
        'transaction_id',
        'status',
        'response',
    ];

    protected $casts = [
        'response' => 'array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function paymentMethod()
    {
        return $this->belongsTo(Payment_method::class);
    }
}
