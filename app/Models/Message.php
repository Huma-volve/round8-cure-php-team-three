<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['message','attachment','is_read','chat_id'];

    public function chats()
    {
        return $this->belongsTo(Chat::class);
    }
}
