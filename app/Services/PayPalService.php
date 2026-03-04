<?php

namespace App\Services;

use Exception;

class PayPalService
{
    public function createInvoice($credentials, $amount, $email, $name)
    {
        $client_id = $credentials['client_id'];
        $secret = $credentials['client_secret'];

        $mode = 'sandbox'; // Update this dynamically if needed: $credentials['mode']

        $baseUrl = $mode === 'live'
            ? 'https://api-m.paypal.com'
            : 'https://api-m.sandbox.paypal.com';

        $client = new \GuzzleHttp\Client();

        // 1. Get Access Token
        $tokenResponse = $client->post($baseUrl . '/v1/oauth2/token', [
            'auth' => [$client_id, $secret],
            'form_params' => [
                'grant_type' => 'client_credentials'
            ]
        ]);

        $tokenData = json_decode($tokenResponse->getBody(), true);
        $accessToken = $tokenData['access_token'];
        $formattedAmount = number_format((float) $amount, 2, '.', '');

        // 2. Create Invoice
        $invoiceResponse = $client->post($baseUrl . '/v2/invoicing/invoices', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json'
            ],
            'json' => [
                "detail" => [
                    "currency_code" => "USD",
                    "note" => "Merchant Invoice Payment"
                ],
                "invoicer" => [
                    "email_address" => "sb-nbx3a28227666@business.example.com"
                ],
                "primary_recipients" => [
                    [
                        "billing_info" => [
                            "email_address" => $email
                        ]
                    ]
                ],
                "items" => [
                    [
                        "name" => "Merchant Fee",
                        "quantity" => "1",
                        "unit_amount" => [
                            "currency_code" => "USD",
                            "value" => $formattedAmount
                        ]
                    ]
                ]
            ]
        ]);

        $invoiceData = json_decode($invoiceResponse->getBody(), true);
        $invoiceHref = $invoiceData['href'];
        $invoiceId = basename($invoiceHref);

        // 3. Send Invoice
        $client->post($baseUrl . "/v2/invoicing/invoices/{$invoiceId}/send", [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'send_to_recipient' => true,
            ]
        ]);

        $invoiceUrl = $mode === 'live'
            ? "https://www.paypal.com/invoice/p/#" . $invoiceId
            : "https://www.sandbox.paypal.com/invoice/p/#" . $invoiceId;

        return [
            'invoice_id' => $invoiceId,
            'payment_link' => $invoiceUrl
        ];
    }
}
