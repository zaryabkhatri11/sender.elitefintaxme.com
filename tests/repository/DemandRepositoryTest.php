<?php

namespace Tests\Repository;

use \App;
use Tests\ApiTestTrait;
use Tests\TestCase;
use \Tests\Traits\MakeDemandTrait;
use App\Models\Demand;
use App\Repositories\Admin\DemandRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DemandRepositoryTest extends TestCase
{
    use MakeDemandTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var DemandRepository
     */
    protected $demandRepo;

    public function setUp()
    {
        parent::setUp();
        $this->demandRepo = App::make(DemandRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateDemand()
    {
        $demand = $this->fakeDemandData();
        $createdDemand = $this->demandRepo->create($demand);
        $createdDemand = $createdDemand->toArray();
        $this->assertArrayHasKey('id', $createdDemand);
        $this->assertNotNull($createdDemand['id'], 'Created Demand must have id specified');
        $this->assertNotNull(Demand::find($createdDemand['id']), 'Demand with given id must be in DB');
        $this->assertModelData($demand, $createdDemand);
    }

    /**
     * @test read
     */
    public function testReadDemand()
    {
        $demand = $this->makeDemand();
        $dbDemand = $this->demandRepo->find($demand->id);
        $dbDemand = $dbDemand->toArray();
        $this->assertModelData($demand->toArray(), $dbDemand);
    }

    /**
     * @test update
     */
    public function testUpdateDemand()
    {
        $demand = $this->makeDemand();
        $fakeDemand = $this->fakeDemandData();
        $updatedDemand = $this->demandRepo->update($fakeDemand, $demand->id);
        $this->assertModelData($fakeDemand, $updatedDemand->toArray());
        $dbDemand = $this->demandRepo->find($demand->id);
        $this->assertModelData($fakeDemand, $dbDemand->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteDemand()
    {
        $demand = $this->makeDemand();
        $resp = $this->demandRepo->delete($demand->id);
        $this->assertTrue($resp);
        $this->assertNull(Demand::find($demand->id), 'Demand should not exist in DB');
    }
}
