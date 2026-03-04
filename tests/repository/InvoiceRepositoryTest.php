<?php

namespace Tests\Repository;

use \App;
use Tests\ApiTestTrait;
use Tests\TestCase;
use \Tests\Traits\MakeInvoiceTrait;
use App\Models\Invoice;
use App\Repositories\Admin\InvoiceRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class InvoiceRepositoryTest extends TestCase
{
    use MakeInvoiceTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var InvoiceRepository
     */
    protected $invoiceRepo;

    public function setUp()
    {
        parent::setUp();
        $this->invoiceRepo = App::make(InvoiceRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateInvoice()
    {
        $invoice = $this->fakeInvoiceData();
        $createdInvoice = $this->invoiceRepo->create($invoice);
        $createdInvoice = $createdInvoice->toArray();
        $this->assertArrayHasKey('id', $createdInvoice);
        $this->assertNotNull($createdInvoice['id'], 'Created Invoice must have id specified');
        $this->assertNotNull(Invoice::find($createdInvoice['id']), 'Invoice with given id must be in DB');
        $this->assertModelData($invoice, $createdInvoice);
    }

    /**
     * @test read
     */
    public function testReadInvoice()
    {
        $invoice = $this->makeInvoice();
        $dbInvoice = $this->invoiceRepo->find($invoice->id);
        $dbInvoice = $dbInvoice->toArray();
        $this->assertModelData($invoice->toArray(), $dbInvoice);
    }

    /**
     * @test update
     */
    public function testUpdateInvoice()
    {
        $invoice = $this->makeInvoice();
        $fakeInvoice = $this->fakeInvoiceData();
        $updatedInvoice = $this->invoiceRepo->update($fakeInvoice, $invoice->id);
        $this->assertModelData($fakeInvoice, $updatedInvoice->toArray());
        $dbInvoice = $this->invoiceRepo->find($invoice->id);
        $this->assertModelData($fakeInvoice, $dbInvoice->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteInvoice()
    {
        $invoice = $this->makeInvoice();
        $resp = $this->invoiceRepo->delete($invoice->id);
        $this->assertTrue($resp);
        $this->assertNull(Invoice::find($invoice->id), 'Invoice should not exist in DB');
    }
}
