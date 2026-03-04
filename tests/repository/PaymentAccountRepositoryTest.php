<?php

namespace Tests\Repository;

use \App;
use Tests\ApiTestTrait;
use Tests\TestCase;
use \Tests\Traits\MakePaymentAccountTrait;
use App\Models\PaymentAccount;
use App\Repositories\Admin\PaymentAccountRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PaymentAccountRepositoryTest extends TestCase
{
    use MakePaymentAccountTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var PaymentAccountRepository
     */
    protected $paymentAccountRepo;

    public function setUp()
    {
        parent::setUp();
        $this->paymentAccountRepo = App::make(PaymentAccountRepository::class);
    }

    /**
     * @test create
     */
    public function testCreatePaymentAccount()
    {
        $paymentAccount = $this->fakePaymentAccountData();
        $createdPaymentAccount = $this->paymentAccountRepo->create($paymentAccount);
        $createdPaymentAccount = $createdPaymentAccount->toArray();
        $this->assertArrayHasKey('id', $createdPaymentAccount);
        $this->assertNotNull($createdPaymentAccount['id'], 'Created PaymentAccount must have id specified');
        $this->assertNotNull(PaymentAccount::find($createdPaymentAccount['id']), 'PaymentAccount with given id must be in DB');
        $this->assertModelData($paymentAccount, $createdPaymentAccount);
    }

    /**
     * @test read
     */
    public function testReadPaymentAccount()
    {
        $paymentAccount = $this->makePaymentAccount();
        $dbPaymentAccount = $this->paymentAccountRepo->find($paymentAccount->id);
        $dbPaymentAccount = $dbPaymentAccount->toArray();
        $this->assertModelData($paymentAccount->toArray(), $dbPaymentAccount);
    }

    /**
     * @test update
     */
    public function testUpdatePaymentAccount()
    {
        $paymentAccount = $this->makePaymentAccount();
        $fakePaymentAccount = $this->fakePaymentAccountData();
        $updatedPaymentAccount = $this->paymentAccountRepo->update($fakePaymentAccount, $paymentAccount->id);
        $this->assertModelData($fakePaymentAccount, $updatedPaymentAccount->toArray());
        $dbPaymentAccount = $this->paymentAccountRepo->find($paymentAccount->id);
        $this->assertModelData($fakePaymentAccount, $dbPaymentAccount->toArray());
    }

    /**
     * @test delete
     */
    public function testDeletePaymentAccount()
    {
        $paymentAccount = $this->makePaymentAccount();
        $resp = $this->paymentAccountRepo->delete($paymentAccount->id);
        $this->assertTrue($resp);
        $this->assertNull(PaymentAccount::find($paymentAccount->id), 'PaymentAccount should not exist in DB');
    }
}
