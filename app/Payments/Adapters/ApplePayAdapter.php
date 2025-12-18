<?php

namespace App\Payments\Adapters;

use App\Payments\PaymentAdapter;

class ApplePayAdapter implements PaymentAdapter
{
    public function pay(float $amount, array $data = []): array
    {
        return [
            'status' => 'pending',
            'transaction_id' => uniqid('cc_'),
        ];

    }
}
