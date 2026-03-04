<?php

namespace Tests\Repository;

use \App;
use Tests\ApiTestTrait;
use Tests\TestCase;
use \Tests\Traits\MakeMerchantTrait;
use App\Models\Merchant;
use App\Repositories\Admin\MerchantRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MerchantRepositoryTest extends TestCase
{
    use MakeMerchantTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var MerchantRepository
     */
    protected $merchantRepo;

    public function setUp()
    {
        parent::setUp();
        $this->merchantRepo = App::make(MerchantRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateMerchant()
    {
        $merchant = $this->fakeMerchantData();
        $createdMerchant = $this->merchantRepo->create($merchant);
        $createdMerchant = $createdMerchant->toArray();
        $this->assertArrayHasKey('id', $createdMerchant);
        $this->assertNotNull($createdMerchant['id'], 'Created Merchant must have id specified');
        $this->assertNotNull(Merchant::find($createdMerchant['id']), 'Merchant with given id must be in DB');
        $this->assertModelData($merchant, $createdMerchant);
    }

    /**
     * @test read
     */
    public function testReadMerchant()
    {
        $merchant = $this->makeMerchant();
        $dbMerchant = $this->merchantRepo->find($merchant->id);
        $dbMerchant = $dbMerchant->toArray();
        $this->assertModelData($merchant->toArray(), $dbMerchant);
    }

    /**
     * @test update
     */
    public function testUpdateMerchant()
    {
        $merchant = $this->makeMerchant();
        $fakeMerchant = $this->fakeMerchantData();
        $updatedMerchant = $this->merchantRepo->update($fakeMerchant, $merchant->id);
        $this->assertModelData($fakeMerchant, $updatedMerchant->toArray());
        $dbMerchant = $this->merchantRepo->find($merchant->id);
        $this->assertModelData($fakeMerchant, $dbMerchant->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteMerchant()
    {
        $merchant = $this->makeMerchant();
        $resp = $this->merchantRepo->delete($merchant->id);
        $this->assertTrue($resp);
        $this->assertNull(Merchant::find($merchant->id), 'Merchant should not exist in DB');
    }
}
