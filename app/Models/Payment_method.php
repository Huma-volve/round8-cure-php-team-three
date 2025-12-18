<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment_method extends Model
{
    protected $fillable = [
        'name',
        'code',
    ];

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
