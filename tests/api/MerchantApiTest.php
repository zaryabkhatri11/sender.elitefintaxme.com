<?php

namespace Tests\Api;

use Tests\ApiTestTrait;
use Tests\TestCase;
use Tests\Traits\MakeMerchantTrait;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MerchantApiTest extends TestCase
{
    use MakeMerchantTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateMerchant()
    {
        $merchant = $this->fakeMerchantData();
        $this->json('POST', '/api/v1/merchants', $merchant);

        $this->assertApiResponse($merchant);
    }

    /**
     * @test
     */
    public function testReadMerchant()
    {
        $merchant = $this->makeMerchant();
        $this->json('GET', '/api/v1/merchants/'.$merchant->id);

        $this->assertApiResponse($merchant->toArray());
    }

    /**
     * @test
     */
    public function testUpdateMerchant()
    {
        $merchant = $this->makeMerchant();
        $editedMerchant = $this->fakeMerchantData();

        $this->json('PUT', '/api/v1/merchants/'.$merchant->id, $editedMerchant);

        $this->assertApiResponse($editedMerchant);
    }

    /**
     * @test
     */
    public function testDeleteMerchant()
    {
        $merchant = $this->makeMerchant();
        $this->json('DELETE', '/api/v1/merchants/'.$merchant->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/merchants/'.$merchant->id);

        $this->assertResponseStatus(404);
    }
}
