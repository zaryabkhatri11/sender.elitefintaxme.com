<?php

namespace Tests\Api;

use Tests\ApiTestTrait;
use Tests\TestCase;
use Tests\Traits\MakeDemandTrait;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DemandApiTest extends TestCase
{
    use MakeDemandTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateDemand()
    {
        $demand = $this->fakeDemandData();
        $this->json('POST', '/api/v1/demands', $demand);

        $this->assertApiResponse($demand);
    }

    /**
     * @test
     */
    public function testReadDemand()
    {
        $demand = $this->makeDemand();
        $this->json('GET', '/api/v1/demands/'.$demand->id);

        $this->assertApiResponse($demand->toArray());
    }

    /**
     * @test
     */
    public function testUpdateDemand()
    {
        $demand = $this->makeDemand();
        $editedDemand = $this->fakeDemandData();

        $this->json('PUT', '/api/v1/demands/'.$demand->id, $editedDemand);

        $this->assertApiResponse($editedDemand);
    }

    /**
     * @test
     */
    public function testDeleteDemand()
    {
        $demand = $this->makeDemand();
        $this->json('DELETE', '/api/v1/demands/'.$demand->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/demands/'.$demand->id);

        $this->assertResponseStatus(404);
    }
}
