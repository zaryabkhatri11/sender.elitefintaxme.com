<?php

namespace Tests\Api;

use Tests\ApiTestTrait;
use Tests\TestCase;
use Tests\Traits\MakeDefinationTrait;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DefinationApiTest extends TestCase
{
    use MakeDefinationTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateDefination()
    {
        $defination = $this->fakeDefinationData();
        $this->json('POST', '/api/v1/definations', $defination);

        $this->assertApiResponse($defination);
    }

    /**
     * @test
     */
    public function testReadDefination()
    {
        $defination = $this->makeDefination();
        $this->json('GET', '/api/v1/definations/'.$defination->id);

        $this->assertApiResponse($defination->toArray());
    }

    /**
     * @test
     */
    public function testUpdateDefination()
    {
        $defination = $this->makeDefination();
        $editedDefination = $this->fakeDefinationData();

        $this->json('PUT', '/api/v1/definations/'.$defination->id, $editedDefination);

        $this->assertApiResponse($editedDefination);
    }

    /**
     * @test
     */
    public function testDeleteDefination()
    {
        $defination = $this->makeDefination();
        $this->json('DELETE', '/api/v1/definations/'.$defination->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/definations/'.$defination->id);

        $this->assertResponseStatus(404);
    }
}
