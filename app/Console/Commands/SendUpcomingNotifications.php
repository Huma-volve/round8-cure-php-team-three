<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Services\NotificationService;
use Carbon\Carbon;

class SendUpcomingNotifications extends Command
{
    /**
     * اسم الأمر signature.
     *
     * @var string
     */
    protected $signature = 'notifications:send-upcoming';

    /**
     * وصف الأمر.
     *
     * @var string
     */
    protected $description = 'Send notifications for upcoming appointments';

    /**
     * تنفيذ الأمر.
     */
    public function handle()
    {
        $notificationService = new NotificationService();

        $tomorrow = Carbon::tomorrow()->toDateString();
        $upcomingBookings = Booking::where('status', 'Upcoming')
            ->where('booking_date', $tomorrow)
            ->get();

        foreach ($upcomingBookings as $booking) {
            try {
                $notificationService->sendUpcomingBookingNotification(
                    $booking->user,
                    $booking
                );
                $this->info("Notification sent for booking #{$booking->id}");
            } catch (\Exception $e) {
                $this->error("Failed to send notification for booking #{$booking->id}: " . $e->getMessage());
            }
        }

        $this->info("Total notifications sent: " . $upcomingBookings->count());
    }
}
