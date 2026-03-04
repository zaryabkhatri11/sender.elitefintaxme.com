<?php

namespace App\Services;

use Exception;

class PaymentGatewayService
{
    protected $payPalService;
    protected $squareService;

    public function __construct(PayPalService $payPalService, SquareService $squareService)
    {
        $this->payPalService = $payPalService;
        $this->squareService = $squareService;
    }

    public function createInvoice($paymentAccount, $amount, $email, $name)
    {
        if ($paymentAccount->gateway === 'paypal') {
            return $this->payPalService->createInvoice($paymentAccount->credentials, $amount, $email, $name);
        } elseif ($paymentAccount->gateway === 'square') {
            return $this->squareService->createInvoice($paymentAccount->credentials, $amount, $email, $name);
        }

        throw new Exception("Unsupported payment gateway: {$paymentAccount->gateway}");
    }
}
