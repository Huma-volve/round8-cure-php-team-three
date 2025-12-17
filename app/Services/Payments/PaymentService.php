<?php

namespace App\Services\Payments;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Payment_method;
use App\Payments\PaymentFactory;

class PaymentService
{
    public function process(
        Booking $booking,
        Payment_method $paymentMethod
    ): Payment {

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'payment_method_id' => $paymentMethod->id,
            'amount' => $booking->price,
            'status' => 'pending',
        ]);

        $adapter = PaymentFactory::make($paymentMethod->code);

        $result = $adapter->pay(
        $booking->price,
        ['booking_id' => $booking->id]
);


        $payment->update([
            'transaction_id' => $result['transaction_id'] ?? null,
            'status' => 'pending',
            'response' => $result,
        ]);

        return $payment;
    }
}

