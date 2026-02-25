<?php

namespace Tests\Repository;

use \App;
use Tests\ApiTestTrait;
use Tests\TestCase;
use \Tests\Traits\MakeSheetTrait;
use App\Models\Sheet;
use App\Repositories\Admin\SheetRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SheetRepositoryTest extends TestCase
{
    use MakeSheetTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var SheetRepository
     */
    protected $sheetRepo;

    public function setUp()
    {
        parent::setUp();
        $this->sheetRepo = App::make(SheetRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateSheet()
    {
        $sheet = $this->fakeSheetData();
        $createdSheet = $this->sheetRepo->create($sheet);
        $createdSheet = $createdSheet->toArray();
        $this->assertArrayHasKey('id', $createdSheet);
        $this->assertNotNull($createdSheet['id'], 'Created Sheet must have id specified');
        $this->assertNotNull(Sheet::find($createdSheet['id']), 'Sheet with given id must be in DB');
        $this->assertModelData($sheet, $createdSheet);
    }

    /**
     * @test read
     */
    public function testReadSheet()
    {
        $sheet = $this->makeSheet();
        $dbSheet = $this->sheetRepo->find($sheet->id);
        $dbSheet = $dbSheet->toArray();
        $this->assertModelData($sheet->toArray(), $dbSheet);
    }

    /**
     * @test update
     */
    public function testUpdateSheet()
    {
        $sheet = $this->makeSheet();
        $fakeSheet = $this->fakeSheetData();
        $updatedSheet = $this->sheetRepo->update($fakeSheet, $sheet->id);
        $this->assertModelData($fakeSheet, $updatedSheet->toArray());
        $dbSheet = $this->sheetRepo->find($sheet->id);
        $this->assertModelData($fakeSheet, $dbSheet->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteSheet()
    {
        $sheet = $this->makeSheet();
        $resp = $this->sheetRepo->delete($sheet->id);
        $this->assertTrue($resp);
        $this->assertNull(Sheet::find($sheet->id), 'Sheet should not exist in DB');
    }
}
