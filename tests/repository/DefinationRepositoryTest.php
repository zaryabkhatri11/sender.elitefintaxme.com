<?php

namespace Tests\Repository;

use \App;
use Tests\ApiTestTrait;
use Tests\TestCase;
use \Tests\Traits\MakeDefinationTrait;
use App\Models\Defination;
use App\Repositories\Admin\DefinationRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DefinationRepositoryTest extends TestCase
{
    use MakeDefinationTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var DefinationRepository
     */
    protected $definationRepo;

    public function setUp()
    {
        parent::setUp();
        $this->definationRepo = App::make(DefinationRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateDefination()
    {
        $defination = $this->fakeDefinationData();
        $createdDefination = $this->definationRepo->create($defination);
        $createdDefination = $createdDefination->toArray();
        $this->assertArrayHasKey('id', $createdDefination);
        $this->assertNotNull($createdDefination['id'], 'Created Defination must have id specified');
        $this->assertNotNull(Defination::find($createdDefination['id']), 'Defination with given id must be in DB');
        $this->assertModelData($defination, $createdDefination);
    }

    /**
     * @test read
     */
    public function testReadDefination()
    {
        $defination = $this->makeDefination();
        $dbDefination = $this->definationRepo->find($defination->id);
        $dbDefination = $dbDefination->toArray();
        $this->assertModelData($defination->toArray(), $dbDefination);
    }

    /**
     * @test update
     */
    public function testUpdateDefination()
    {
        $defination = $this->makeDefination();
        $fakeDefination = $this->fakeDefinationData();
        $updatedDefination = $this->definationRepo->update($fakeDefination, $defination->id);
        $this->assertModelData($fakeDefination, $updatedDefination->toArray());
        $dbDefination = $this->definationRepo->find($defination->id);
        $this->assertModelData($fakeDefination, $dbDefination->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteDefination()
    {
        $defination = $this->makeDefination();
        $resp = $this->definationRepo->delete($defination->id);
        $this->assertTrue($resp);
        $this->assertNull(Defination::find($defination->id), 'Defination should not exist in DB');
    }
}
