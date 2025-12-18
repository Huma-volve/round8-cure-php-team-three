<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use App\Models\Notification as NotificationModel;

class CustomDatabaseChannel
{
    /**
     * Send the given notification.
     */
    public function send(object $notifiable, Notification $notification): void
    {
        $data = $notification->toArray($notifiable);

        NotificationModel::create([
            'user_id' => $notifiable->id,
            'title' => $data['title'] ?? 'Notification',
            'body' => $data['message'] ?? $data['body'] ?? '',
            'is_read' => false,
        ]);
    }
}
