<?php

namespace App\Notifications;

use App\Models\Booking;
use App\Notifications\Channels\CustomDatabaseChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpcomingAppointmentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $booking;

    /**
     * Create a new notification instance.
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', CustomDatabaseChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $doctorName = $this->booking->doctor->name ?? 'Doctor';
        $date = $this->booking->booking_date;
        $time = $this->booking->booking_time;

        return (new MailMessage)
            ->subject('Upcoming Appointment Reminder')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line("This is a reminder for your upcoming appointment with Dr. {$doctorName}.")
            ->line("Date: {$date}")
            ->line("Time: {$time}")
            ->action('View Appointment Details', url('/bookings/' . $this->booking->id))
            ->line('Please arrive 10 minutes early.')
            ->line('Thank you for choosing our service!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $doctorName = $this->booking->doctor->name ?? 'Doctor';
        
        return [
            'booking_id' => $this->booking->id,
            'title' => 'Upcoming Appointment',
            'message' => "You have an appointment with Dr. {$doctorName} on {$this->booking->booking_date} at {$this->booking->booking_time}.",
            'link' => url('/bookings/' . $this->booking->id),
        ];
    }
}
