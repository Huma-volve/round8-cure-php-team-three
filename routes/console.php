<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\Booking;
use App\Services\NotificationService;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('notifications:send-upcoming', function () {
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
})->purpose('Send notifications for upcoming appointments');
