<?php

namespace Tests\Api;

use Tests\ApiTestTrait;
use Tests\TestCase;
use Tests\Traits\MakeCustomerTrait;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CustomerApiTest extends TestCase
{
    use MakeCustomerTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateCustomer()
    {
        $customer = $this->fakeCustomerData();
        $this->json('POST', '/api/v1/customers', $customer);

        $this->assertApiResponse($customer);
    }

    /**
     * @test
     */
    public function testReadCustomer()
    {
        $customer = $this->makeCustomer();
        $this->json('GET', '/api/v1/customers/'.$customer->id);

        $this->assertApiResponse($customer->toArray());
    }

    /**
     * @test
     */
    public function testUpdateCustomer()
    {
        $customer = $this->makeCustomer();
        $editedCustomer = $this->fakeCustomerData();

        $this->json('PUT', '/api/v1/customers/'.$customer->id, $editedCustomer);

        $this->assertApiResponse($editedCustomer);
    }

    /**
     * @test
     */
    public function testDeleteCustomer()
    {
        $customer = $this->makeCustomer();
        $this->json('DELETE', '/api/v1/customers/'.$customer->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/customers/'.$customer->id);

        $this->assertResponseStatus(404);
    }
}
