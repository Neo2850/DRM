<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;
use Illuminate\Support\Facades\Session;

class PaymentController extends Controller
{
    public function pay()
    {
        $data = [
            'data' => [
                'attributes' => [
                    'line_items' => [
                        [
                            'currency' => 'PHP',
                            'amount' => 10000,
                            'description' => 'Payment for your order',
                            'name' => 'Test',
                            'quantity' => 1,
                        ],
                    ],
                    'payment_method_types' => ['gcash'],
                    'success_url' => 'http://localhost:8000',
                    'cancel_url' => 'http://localhost:8000/customer/checkout',
                    'description' => 'text',
                    'billing' => [
                        'name' => 'Momo Revillame',
                        'email' => 'momo@gmail.com',
                        'phone' => '09123456789',
                        'address' => [
                            'line1' => '123 Main St',
                            'city' => 'Manila',
                            'state' => 'NCR',
                            'postal_code' => '12345',
                        ]
                    ]
                ],
            ],
        ];

        $response = Curl::to('https://api.paymongo.com/v1/checkout_sessions')
            ->withHeader('Content-Type: application/json')
            ->withHeader('Accept: application/json')
            ->withHeader('Authorization: Basic ' . base64_encode(env('AUTH_PAY') . ':'))
            ->withData($data)
            ->asJson()
            ->post();



        Session::put('session_id', $response->data->id);

        return redirect($response->data->attributes->checkout_url);
    }

    public function success()
    {
        $session_id = Session::get('session_id');
    }
}
