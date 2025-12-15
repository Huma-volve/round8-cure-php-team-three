<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['sender_id','sender_to_id','message_id','is_read'];

    public function usermassage()
    {
        return $this->belongsTo(User::class,'sender_id');
    }

    public function userMessageTo()
    {
        return $this->belongsTo(User::class,'sender_to_id');
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
