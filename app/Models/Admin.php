<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
   protected $guarded = [];

   public function user()
    {
        return $this->belongsTo(User::class);
    }

   public function notifications()
   {
       return $this->hasMany(Notification::class);
   }
   
   public function unreadNotifications()
   {
       return $this->notifications()->where('is_read', false);
   }
}

