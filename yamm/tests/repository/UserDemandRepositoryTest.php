<?php

namespace Tests\Repository;

use \App;
use Tests\ApiTestTrait;
use Tests\TestCase;
use \Tests\Traits\MakeUserDemandTrait;
use App\Models\UserDemand;
use App\Repositories\Admin\UserDemandRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserDemandRepositoryTest extends TestCase
{
    use MakeUserDemandTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var UserDemandRepository
     */
    protected $userDemandRepo;

    public function setUp()
    {
        parent::setUp();
        $this->userDemandRepo = App::make(UserDemandRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateUserDemand()
    {
        $userDemand = $this->fakeUserDemandData();
        $createdUserDemand = $this->userDemandRepo->create($userDemand);
        $createdUserDemand = $createdUserDemand->toArray();
        $this->assertArrayHasKey('id', $createdUserDemand);
        $this->assertNotNull($createdUserDemand['id'], 'Created UserDemand must have id specified');
        $this->assertNotNull(UserDemand::find($createdUserDemand['id']), 'UserDemand with given id must be in DB');
        $this->assertModelData($userDemand, $createdUserDemand);
    }

    /**
     * @test read
     */
    public function testReadUserDemand()
    {
        $userDemand = $this->makeUserDemand();
        $dbUserDemand = $this->userDemandRepo->find($userDemand->id);
        $dbUserDemand = $dbUserDemand->toArray();
        $this->assertModelData($userDemand->toArray(), $dbUserDemand);
    }

    /**
     * @test update
     */
    public function testUpdateUserDemand()
    {
        $userDemand = $this->makeUserDemand();
        $fakeUserDemand = $this->fakeUserDemandData();
        $updatedUserDemand = $this->userDemandRepo->update($fakeUserDemand, $userDemand->id);
        $this->assertModelData($fakeUserDemand, $updatedUserDemand->toArray());
        $dbUserDemand = $this->userDemandRepo->find($userDemand->id);
        $this->assertModelData($fakeUserDemand, $dbUserDemand->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteUserDemand()
    {
        $userDemand = $this->makeUserDemand();
        $resp = $this->userDemandRepo->delete($userDemand->id);
        $this->assertTrue($resp);
        $this->assertNull(UserDemand::find($userDemand->id), 'UserDemand should not exist in DB');
    }
}
