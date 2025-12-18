<?php

namespace App\Payments\Adapters;

use App\Payments\PaymentAdapter;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class CreditCardAdapter implements PaymentAdapter
{
    public function pay(float $amount, array $data = []): array
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $intent = PaymentIntent::create([
            'amount' => (int) ($amount * 100),
            'currency' => 'usd',
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);

        return [
            'status' => 'pending',
            'transaction_id' => $intent->id,
            'client_secret' => $intent->client_secret,
        ];
    }
}
