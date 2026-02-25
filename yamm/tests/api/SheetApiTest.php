<?php

namespace Tests\Api;

use Tests\ApiTestTrait;
use Tests\TestCase;
use Tests\Traits\MakeSheetTrait;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SheetApiTest extends TestCase
{
    use MakeSheetTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateSheet()
    {
        $sheet = $this->fakeSheetData();
        $this->json('POST', '/api/v1/sheets', $sheet);

        $this->assertApiResponse($sheet);
    }

    /**
     * @test
     */
    public function testReadSheet()
    {
        $sheet = $this->makeSheet();
        $this->json('GET', '/api/v1/sheets/'.$sheet->id);

        $this->assertApiResponse($sheet->toArray());
    }

    /**
     * @test
     */
    public function testUpdateSheet()
    {
        $sheet = $this->makeSheet();
        $editedSheet = $this->fakeSheetData();

        $this->json('PUT', '/api/v1/sheets/'.$sheet->id, $editedSheet);

        $this->assertApiResponse($editedSheet);
    }

    /**
     * @test
     */
    public function testDeleteSheet()
    {
        $sheet = $this->makeSheet();
        $this->json('DELETE', '/api/v1/sheets/'.$sheet->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/sheets/'.$sheet->id);

        $this->assertResponseStatus(404);
    }
}
