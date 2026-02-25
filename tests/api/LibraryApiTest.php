<?php

namespace Tests\Api;

use Tests\ApiTestTrait;
use Tests\TestCase;
use Tests\Traits\MakeLibraryTrait;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LibraryApiTest extends TestCase
{
    use MakeLibraryTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateLibrary()
    {
        $library = $this->fakeLibraryData();
        $this->json('POST', '/api/v1/libraries', $library);

        $this->assertApiResponse($library);
    }

    /**
     * @test
     */
    public function testReadLibrary()
    {
        $library = $this->makeLibrary();
        $this->json('GET', '/api/v1/libraries/'.$library->id);

        $this->assertApiResponse($library->toArray());
    }

    /**
     * @test
     */
    public function testUpdateLibrary()
    {
        $library = $this->makeLibrary();
        $editedLibrary = $this->fakeLibraryData();

        $this->json('PUT', '/api/v1/libraries/'.$library->id, $editedLibrary);

        $this->assertApiResponse($editedLibrary);
    }

    /**
     * @test
     */
    public function testDeleteLibrary()
    {
        $library = $this->makeLibrary();
        $this->json('DELETE', '/api/v1/libraries/'.$library->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/libraries/'.$library->id);

        $this->assertResponseStatus(404);
    }
}
