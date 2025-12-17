<?php

namespace App\Payments;

interface PaymentAdapter
{
    public function pay(float $amount, array $data = []): array;
}
