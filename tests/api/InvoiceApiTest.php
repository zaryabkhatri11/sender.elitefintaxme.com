<?php

namespace Tests\Api;

use Tests\ApiTestTrait;
use Tests\TestCase;
use Tests\Traits\MakeInvoiceTrait;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InvoiceApiTest extends TestCase
{
    use MakeInvoiceTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateInvoice()
    {
        $invoice = $this->fakeInvoiceData();
        $this->json('POST', '/api/v1/invoices', $invoice);

        $this->assertApiResponse($invoice);
    }

    /**
     * @test
     */
    public function testReadInvoice()
    {
        $invoice = $this->makeInvoice();
        $this->json('GET', '/api/v1/invoices/'.$invoice->id);

        $this->assertApiResponse($invoice->toArray());
    }

    /**
     * @test
     */
    public function testUpdateInvoice()
    {
        $invoice = $this->makeInvoice();
        $editedInvoice = $this->fakeInvoiceData();

        $this->json('PUT', '/api/v1/invoices/'.$invoice->id, $editedInvoice);

        $this->assertApiResponse($editedInvoice);
    }

    /**
     * @test
     */
    public function testDeleteInvoice()
    {
        $invoice = $this->makeInvoice();
        $this->json('DELETE', '/api/v1/invoices/'.$invoice->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/invoices/'.$invoice->id);

        $this->assertResponseStatus(404);
    }
}
