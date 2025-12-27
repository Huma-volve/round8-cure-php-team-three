<?php

namespace Database\Seeders;

use App\Models\Payment_method;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Payment_method::create([
        //     'name' => 'Credit Card',
        //     'code' => 'credit_card',
        // ]);

        Payment_method::create([
            'name' => 'PayPal',
            'code' => 'paypal',
        ]);

        Payment_method::create([
            'name' => 'Apple Pay',
            'code' => 'apple_pay',
        ]);
    }
}
