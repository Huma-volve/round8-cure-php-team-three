<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Booking;
use App\Models\Doctor;

class NotificationService
{

    public function sendToPatient(User $patient, string $title, string $body)
    {
        return Notification::create([
            'user_id' => $patient->id,
            'title' => $title,
            'body' => $body,
            'is_read' => false
        ]);
    }


public function sendToDoctor($doctor, string $title, string $body)
{
    if ($doctor instanceof Doctor) {
        $userId = $doctor->user_id;
    }
    else {
        $userId = $doctor->id;
    }

    return Notification::create([
        'user_id' => $userId,
        'title' => $title,
        'body' => $body,
        'is_read' => false
    ]);
}


    public function sendToAdmin(User $admin, string $title, string $body)
    {
        return Notification::create([
            'user_id' => $admin->id,
            'title' => $title,
            'body' => $body,
            'is_read' => false
        ]);
    }


    public function sendUpcomingBookingNotification(User $patient, Booking $booking)
    {
        return $this->sendToPatient(
            $patient,
            'Upcoming Appointment',
            "Reminder: You have an appointment with Dr. {$booking->doctor->name} at {$booking->booking_time} on {$booking->booking_date}"
        );
    }


    public function sendBookingCancelledNotification(User $patient, Booking $booking)
    {
        return $this->sendToPatient(
            $patient,
            'Appointment Cancelled',
            "You have successfully cancelled your appointment with Dr. {$booking->doctor->name}"
        );
    }


    public function sendBookingRescheduledNotification(User $patient, Booking $booking, array $oldData)
    {
        return $this->sendToPatient(
            $patient,
            'Appointment Rescheduled',
            "Your appointment with Dr. {$booking->doctor->name} has been rescheduled from {$oldData['old_date']} {$oldData['old_time']} to {$booking->booking_date} {$booking->booking_time}"
        );
    }


    public function sendNewBookingNotification($doctor, Booking $booking)
    {
    return $this->sendToDoctor(
        $doctor,
        'New Booking Request',
        "You have a new booking request from {$booking->user->name} for {$booking->booking_date}"
    );
    }


public function sendNewReviewNotification($doctor, array $reviewData)
{
    return $this->sendToDoctor(
        $doctor,
        'New Review Received',
        "You received a {$reviewData['rating']}-star review from {$reviewData['patient_name']}"
    );
}


    public function sendNewChatNotification(User $doctor, array $chatData)
    {
        return $this->sendToDoctor(
            $doctor,
            'New Message',
            "New message from {$chatData['patient_name']}: {$chatData['message_preview']}"
        );
    }


    public function sendPaymentReceivedNotification(User $doctor, array $paymentData)
    {
        return $this->sendToDoctor(
            $doctor,
            'Payment Received',
            "Payment of {$paymentData['amount']} received for booking #{$paymentData['booking_id']}"
        );
    }


    public function sendSystemAlertNotification(User $admin, string $alertTitle, string $alertMessage)
    {
        return $this->sendToAdmin(
            $admin,
            $alertTitle,
            $alertMessage
        );
    }


    public function sendBroadcastNotification(array $userIds, string $title, string $message)
    {
        $notifications = [];

        foreach ($userIds as $userId) {
            $notifications[] = Notification::create([
                'user_id' => $userId,
                'title' => $title,
                'body' => $message,
                'is_read' => false
            ]);
        }

        return $notifications;
    }


    public function broadcastToPatients(string $title, string $message)
    {
        $patientIds = User::where('role', 'patient')
            ->where('is_active', true)
            ->pluck('id')
            ->toArray();

        return $this->sendBroadcastNotification($patientIds, $title, $message);
    }


    public function broadcastToDoctors(string $title, string $message)
    {
        $doctorIds = User::where('role', 'doctor')
            ->where('is_active', true)
            ->pluck('id')
            ->toArray();

        return $this->sendBroadcastNotification($doctorIds, $title, $message);
    }


    public function broadcastToAll(string $title, string $message)
    {
        $allUserIds = User::where('is_active', true)
            ->pluck('id')
            ->toArray();

        return $this->sendBroadcastNotification($allUserIds, $title, $message);
    }

    
}
