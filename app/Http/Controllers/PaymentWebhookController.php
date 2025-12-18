<?php

namespace App\Http\Controllers;

use App\Enums\BookingStatus;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Stripe webhook received', $request->all());

        $event = $request->input('type');
        $intent = $request->input('data.object');

        if ($event === 'payment_intent.succeeded') {
            $payment = Payment::where('transaction_id', $intent['id'])->first();

            if ($payment) {
                $payment->update(['status' => 'success']);
                $payment->booking->update(['status' => BookingStatus::Completed]);
            }
            return response()->json(['ok' => true]);
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
            }
            return response()->json(['ok' => false]);
        }
        
    }
}
