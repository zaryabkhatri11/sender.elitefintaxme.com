<?php

namespace App\Services;

use Exception;
use Square\SquareClient;
use Square\Models\CreateCustomerRequest;
use Square\Models\CreateOrderRequest;
use Square\Models\InvoiceAcceptedPaymentMethods;
use Square\Models\Order;
use Square\Models\OrderLineItem;
use Square\Models\CreateInvoiceRequest;
use Square\Models\Invoice;
use Square\Models\InvoicePaymentRequest;
use Square\Models\InvoiceRecipient;
use Square\Models\Money;
use Square\Models\PublishInvoiceRequest;

class SquareService
{
    public function createInvoice($credentials, $amount, $email, $name)
    {
        $accessToken = $credentials['access_token'] ?? '';
        $location_id = $credentials['location_id'] ?? '';
        $environment = $credentials['environment'] ?? 'sandbox';

        $client = new SquareClient([
            'accessToken' => $accessToken,
            'environment' => $environment,
        ]);

        // 1. Create Customer
        $customersApi = $client->getCustomersApi();
        $customerReq = new CreateCustomerRequest();
        $customerReq->setEmailAddress($email);
        $customerReq->setGivenName($name);
        $customerReq->setIdempotencyKey(uniqid());

        $customerResponse = $customersApi->createCustomer($customerReq);
        if (!$customerResponse->isSuccess()) {
            throw new Exception('Square Customer Error: ' . json_encode($customerResponse->getErrors()));
        }
        $customerId = $customerResponse->getResult()->getCustomer()->getId();

        // 2. Create Order
        $ordersApi = $client->getOrdersApi();
        $order = new Order($location_id);
        $order->setCustomerId($customerId);
        $lineItem = new OrderLineItem('1');
        $lineItem->setName('Merchant Fee');

        $money = new Money();
        $money->setAmount($amount * 100); // 10.00 = 1000 cents
        $money->setCurrency('USD');

        $lineItem->setBasePriceMoney($money);
        $order->setLineItems([$lineItem]);

        $orderReq = new CreateOrderRequest();
        $orderReq->setOrder($order);
        $orderReq->setIdempotencyKey(uniqid());

        $orderResponse = $ordersApi->createOrder($orderReq);
        if (!$orderResponse->isSuccess()) {
            throw new Exception('Square Order Error: ' . json_encode($orderResponse->getErrors()));
        }
        $orderId = $orderResponse->getResult()->getOrder()->getId();

        // 3. Create Invoice
        $invoicesApi = $client->getInvoicesApi();

        $paymentRequest = new InvoicePaymentRequest();
        $paymentRequest->setRequestMethod('EMAIL'); // EMAIL or SHARE_MANUALLY
        $paymentRequest->setRequestType('BALANCE');
        $paymentRequest->setDueDate(date('Y-m-d')); // Today

        $recipient = new InvoiceRecipient();
        $recipient->setCustomerId($customerId);

        $acceptedPaymentMethods = new InvoiceAcceptedPaymentMethods();
        $acceptedPaymentMethods->setCard(true);
        $acceptedPaymentMethods->setSquareGiftCard(false);
        $acceptedPaymentMethods->setBankAccount(true);

        $invoice = new Invoice();
        $invoice->setLocationId($location_id);
        $invoice->setOrderId($orderId);
        $invoice->setPrimaryRecipient($recipient);
        $invoice->setPaymentRequests([$paymentRequest]);
        $invoice->setTitle('Merchant Invoice Payment');
        $invoice->setAcceptedPaymentMethods($acceptedPaymentMethods);

        $createInvoiceRequest = new CreateInvoiceRequest($invoice);
        $createInvoiceRequest->setIdempotencyKey(uniqid());

        $response = $invoicesApi->createInvoice($createInvoiceRequest);

        if (!$response->isSuccess()) {
            throw new Exception('Square Invoice Creation Error: ' . json_encode($response->getErrors()));
        }

        $invoiceResult = $response->getResult()->getInvoice();
        $publishRequest = new PublishInvoiceRequest($invoiceResult->getVersion());
        $publishResponse = $invoicesApi->publishInvoice($invoiceResult->getId(), $publishRequest);

        if (!$publishResponse->isSuccess()) {
            throw new Exception('Square Invoice Publish Error: ' . json_encode($publishResponse->getErrors()));
        }

        $hostedUrl = $publishResponse->getResult()->getInvoice()->getPublicUrl();

        return [
            'invoice_id' => $invoiceResult->getId(),
            'payment_link' => $hostedUrl
        ];
    }
}
