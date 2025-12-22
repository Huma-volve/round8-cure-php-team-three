<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Chat;
use App\Services\NotificationService;

class Message extends Model
{
    protected $fillable = ['message','attachment','is_read','chat_id'];

    public function chats()
    {
        return $this->belongsTo(Chat::class);
    }

    protected static function booted(): void
    {
        static::created(function (Message $message) {
            $chat = Chat::find($message->chat_id);
            if (!$chat) {
                return;
            }

            $toUser = User::find($chat->sender_to_id);
            $fromUser = User::find($chat->sender_id);
            if (!$toUser || !$fromUser) {
                return;
            }

            $isDoctor = method_exists($toUser, 'hasRole') ? $toUser->hasRole('doctor') : (bool) $toUser->doctor;
            if (!$isDoctor) {
                return;
            }

            (new NotificationService())->sendNewChatNotification($toUser, [
                'patient_name' => $fromUser->name,
                'message_preview' => Str::limit((string) $message->message, 80)
            ]);
        });
    }
}
