<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
   protected $guarded = [];

   public function user()
    {
        return $this->belongsTo(User::class);
    }
}

