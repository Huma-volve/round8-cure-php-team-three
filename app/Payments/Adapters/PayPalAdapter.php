<?php

namespace App\Payments\Adapters;

use App\Payments\PaymentAdapter;

class PayPalAdapter implements PaymentAdapter
{
    public function pay(float $amount, array $data = []): array
    {
        return [
            'status' => 'pending',
            'transaction_id' => uniqid('cc_'),
        ];
        // $client = new Client(['base_uri' => 'https://api-m.sandbox.paypal.com/']);

        // // 1️⃣ الحصول على access token
        // $response = $client->post('v1/oauth2/token', [
        //     'auth' => [config('services.paypal.client_id'), config('services.paypal.secret')],
        //     'form_params' => ['grant_type' => 'client_credentials'],
        // ]);

        // $body = json_decode($response->getBody(), true);
        // $accessToken = $body['access_token'];

        // // 2️⃣ إنشاء الدفع
        // $response = $client->post('v2/checkout/orders', [
        //     'headers' => [
        //         'Authorization' => "Bearer $accessToken",
        //         'Content-Type' => 'application/json',
        //     ],
        //     'json' => [
        //         'intent' => 'CAPTURE',
        //         'purchase_units' => [
        //             [
        //                 'amount' => [
        //                     'currency_code' => 'USD',
        //                     'value' => $amount
        //                 ]
        //             ]
        //         ],
        //         'application_context' => [
        //             'return_url' => route('paypal.success'),
        //             'cancel_url' => route('paypal.cancel'),
        //         ]
        //     ]
        // ]);

        // $order = json_decode($response->getBody(), true);

        // return [
        //     'status' => 'pending',
        //     'transaction_id' => $order['id'],
        //     'approval_url' => $order['links'][1]['href'], // رابط دفع PayPal
        // ];
    }
}

