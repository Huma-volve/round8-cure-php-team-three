<?php

namespace App\Payments;

use App\Payments\Adapters\{
    CreditCardAdapter,
    PayPalAdapter,
    ApplePayAdapter
};

class PaymentFactory
{
    public static function make(string $method)
    {
        return match ($method) {
            'credit_card' => new CreditCardAdapter(),
            'paypal'      => new PayPalAdapter(),
            'apple_pay'   => new ApplePayAdapter(),
            default       => throw new \Exception('Invalid payment method'),
        };
    }
}
