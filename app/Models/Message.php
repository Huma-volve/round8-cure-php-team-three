<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['message','attachment'];

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
}
