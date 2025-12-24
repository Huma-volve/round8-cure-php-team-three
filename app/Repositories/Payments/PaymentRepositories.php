<?php

namespace App\Repositories\Payments;

use App\Models\Payment;

class PaymentRepositories
{
    public function create(array $data): Payment
    {
        return Payment::create($data);
    }

    public function update(Payment $payment, array $data): Payment
    {
        $payment->update($data);
        return $payment;
    }

    public function findById($id): ?Payment
    {
        return Payment::findOrFail($id);
    }

    public function delete(Payment $payment): void
    {
        $payment->delete();
    }
}
