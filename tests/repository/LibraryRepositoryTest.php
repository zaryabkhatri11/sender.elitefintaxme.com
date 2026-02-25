<?php

namespace Tests\Repository;

use \App;
use Tests\ApiTestTrait;
use Tests\TestCase;
use \Tests\Traits\MakeLibraryTrait;
use App\Models\Library;
use App\Repositories\Admin\LibraryRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LibraryRepositoryTest extends TestCase
{
    use MakeLibraryTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var LibraryRepository
     */
    protected $libraryRepo;

    public function setUp()
    {
        parent::setUp();
        $this->libraryRepo = App::make(LibraryRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateLibrary()
    {
        $library = $this->fakeLibraryData();
        $createdLibrary = $this->libraryRepo->create($library);
        $createdLibrary = $createdLibrary->toArray();
        $this->assertArrayHasKey('id', $createdLibrary);
        $this->assertNotNull($createdLibrary['id'], 'Created Library must have id specified');
        $this->assertNotNull(Library::find($createdLibrary['id']), 'Library with given id must be in DB');
        $this->assertModelData($library, $createdLibrary);
    }

    /**
     * @test read
     */
    public function testReadLibrary()
    {
        $library = $this->makeLibrary();
        $dbLibrary = $this->libraryRepo->find($library->id);
        $dbLibrary = $dbLibrary->toArray();
        $this->assertModelData($library->toArray(), $dbLibrary);
    }

    /**
     * @test update
     */
    public function testUpdateLibrary()
    {
        $library = $this->makeLibrary();
        $fakeLibrary = $this->fakeLibraryData();
        $updatedLibrary = $this->libraryRepo->update($fakeLibrary, $library->id);
        $this->assertModelData($fakeLibrary, $updatedLibrary->toArray());
        $dbLibrary = $this->libraryRepo->find($library->id);
        $this->assertModelData($fakeLibrary, $dbLibrary->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteLibrary()
    {
        $library = $this->makeLibrary();
        $resp = $this->libraryRepo->delete($library->id);
        $this->assertTrue($resp);
        $this->assertNull(Library::find($library->id), 'Library should not exist in DB');
    }
}
