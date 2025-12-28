<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Enums\BookingStatus;
use App\Models\Payment;
use App\Repositories\Bookings\BookingsRepositories;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Webhook;

class PaymentWebhookController extends Controller
{
    public function __construct(
        protected BookingsRepositories $bookingsRepositories,
    )
    {}
    public function handle(Request $request)
    {
        Log::info('Stripe webhook received', $request->all());

    //     $payload = $request->getContent();
    //     $sigHeader = $request->header('Stripe-Signature');
    //     $secret = config('services.stripe.webhook_secret');

    // try {
    //     $event = Webhook::constructEvent($payload, $sigHeader, $secret);
    // } catch (\Throwable $e) {
    //     return response()->json(['error' => 'Invalid signature'], 400);
    // }

        $event = $request->input('type');
        $intent = $request->input('data.object');

        // $intent = $event->data->object;

        if ($event === 'payment_intent.succeeded') {
            \Log::info('Payment Intent Succeeded', $intent);
            $payment = Payment::where('transaction_id', $intent['id'])->first();

            if ($payment) {
                $payment->update([
                    'status' => 'success',
                    'response' => $intent
                ]);
                $payment->booking->update(['status' => BookingStatus::Upcoming]);

                // $this->bookingsRepositories->deleteAppointment($payment->booking);
                // $payment->booking->update(['status' => BookingStatus::Completed]);

                $doctorUser = optional($payment->booking->doctor)->user;
                if ($doctorUser) {
                    (new NotificationService())->sendPaymentReceivedNotification($doctorUser, [
                        'amount' => $payment->amount,
                        'booking_id' => $payment->booking_id,
                    ]);
                }

                $admins = method_exists(User::class, 'role') ? User::role('admin')->get() : collect();
                foreach ($admins as $admin) {
                    (new NotificationService())->sendSystemAlertNotification($admin, 'Payment Succeeded', 'Payment completed for booking #'.$payment->booking_id);
                }
            }
            return response()->json([
                'ok' => true,
                'message' => 'Payment completed successfully',
                'payment' => $payment,
                'booking' => $payment->booking,
            ]);
        }

        if ($event === 'payment_intent.payment_failed') {
            $payment = Payment::where('transaction_id', $intent['id'])->first();

            if ($payment) {
                $payment->update([
                    'status' => 'failed',
                    'response' => $intent
                ]);

                $payment->booking->update([
                    'status' => BookingStatus::Cancelled
                ]);

                $this->bookingsRepositories->restoreAppointment($payment->booking);

                $admins = method_exists(User::class, 'role') ? User::role('admin')->get() : collect();
                foreach ($admins as $admin) {
                    (new NotificationService())->sendSystemAlertNotification($admin, 'Payment Failed', 'Payment failed for booking #'.$payment->booking_id);
                }
            }
            return response()->json(['ok' => false]);
        }

    }
}
