<?php

namespace Tests\Api;

use Tests\ApiTestTrait;
use Tests\TestCase;
use Tests\Traits\MakePaymentAccountTrait;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PaymentAccountApiTest extends TestCase
{
    use MakePaymentAccountTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreatePaymentAccount()
    {
        $paymentAccount = $this->fakePaymentAccountData();
        $this->json('POST', '/api/v1/payment-accounts', $paymentAccount);

        $this->assertApiResponse($paymentAccount);
    }

    /**
     * @test
     */
    public function testReadPaymentAccount()
    {
        $paymentAccount = $this->makePaymentAccount();
        $this->json('GET', '/api/v1/payment-accounts/'.$paymentAccount->id);

        $this->assertApiResponse($paymentAccount->toArray());
    }

    /**
     * @test
     */
    public function testUpdatePaymentAccount()
    {
        $paymentAccount = $this->makePaymentAccount();
        $editedPaymentAccount = $this->fakePaymentAccountData();

        $this->json('PUT', '/api/v1/payment-accounts/'.$paymentAccount->id, $editedPaymentAccount);

        $this->assertApiResponse($editedPaymentAccount);
    }

    /**
     * @test
     */
    public function testDeletePaymentAccount()
    {
        $paymentAccount = $this->makePaymentAccount();
        $this->json('DELETE', '/api/v1/payment-accounts/'.$paymentAccount->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/payment-accounts/'.$paymentAccount->id);

        $this->assertResponseStatus(404);
    }
}
