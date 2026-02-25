<?php

namespace Tests\Repository;

use \App;
use Tests\ApiTestTrait;
use Tests\TestCase;
use \Tests\Traits\MakeWebinarTrait;
use App\Models\Webinar;
use App\Repositories\Admin\WebinarRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WebinarRepositoryTest extends TestCase
{
    use MakeWebinarTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var WebinarRepository
     */
    protected $webinarRepo;

    public function setUp()
    {
        parent::setUp();
        $this->webinarRepo = App::make(WebinarRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateWebinar()
    {
        $webinar = $this->fakeWebinarData();
        $createdWebinar = $this->webinarRepo->create($webinar);
        $createdWebinar = $createdWebinar->toArray();
        $this->assertArrayHasKey('id', $createdWebinar);
        $this->assertNotNull($createdWebinar['id'], 'Created Webinar must have id specified');
        $this->assertNotNull(Webinar::find($createdWebinar['id']), 'Webinar with given id must be in DB');
        $this->assertModelData($webinar, $createdWebinar);
    }

    /**
     * @test read
     */
    public function testReadWebinar()
    {
        $webinar = $this->makeWebinar();
        $dbWebinar = $this->webinarRepo->find($webinar->id);
        $dbWebinar = $dbWebinar->toArray();
        $this->assertModelData($webinar->toArray(), $dbWebinar);
    }

    /**
     * @test update
     */
    public function testUpdateWebinar()
    {
        $webinar = $this->makeWebinar();
        $fakeWebinar = $this->fakeWebinarData();
        $updatedWebinar = $this->webinarRepo->update($fakeWebinar, $webinar->id);
        $this->assertModelData($fakeWebinar, $updatedWebinar->toArray());
        $dbWebinar = $this->webinarRepo->find($webinar->id);
        $this->assertModelData($fakeWebinar, $dbWebinar->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteWebinar()
    {
        $webinar = $this->makeWebinar();
        $resp = $this->webinarRepo->delete($webinar->id);
        $this->assertTrue($resp);
        $this->assertNull(Webinar::find($webinar->id), 'Webinar should not exist in DB');
    }
}
