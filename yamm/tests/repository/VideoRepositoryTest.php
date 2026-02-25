<?php

namespace Tests\Repository;

use \App;
use Tests\ApiTestTrait;
use Tests\TestCase;
use \Tests\Traits\MakeVideoTrait;
use App\Models\Video;
use App\Repositories\Admin\VideoRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VideoRepositoryTest extends TestCase
{
    use MakeVideoTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var VideoRepository
     */
    protected $videoRepo;

    public function setUp()
    {
        parent::setUp();
        $this->videoRepo = App::make(VideoRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateVideo()
    {
        $video = $this->fakeVideoData();
        $createdVideo = $this->videoRepo->create($video);
        $createdVideo = $createdVideo->toArray();
        $this->assertArrayHasKey('id', $createdVideo);
        $this->assertNotNull($createdVideo['id'], 'Created Video must have id specified');
        $this->assertNotNull(Video::find($createdVideo['id']), 'Video with given id must be in DB');
        $this->assertModelData($video, $createdVideo);
    }

    /**
     * @test read
     */
    public function testReadVideo()
    {
        $video = $this->makeVideo();
        $dbVideo = $this->videoRepo->find($video->id);
        $dbVideo = $dbVideo->toArray();
        $this->assertModelData($video->toArray(), $dbVideo);
    }

    /**
     * @test update
     */
    public function testUpdateVideo()
    {
        $video = $this->makeVideo();
        $fakeVideo = $this->fakeVideoData();
        $updatedVideo = $this->videoRepo->update($fakeVideo, $video->id);
        $this->assertModelData($fakeVideo, $updatedVideo->toArray());
        $dbVideo = $this->videoRepo->find($video->id);
        $this->assertModelData($fakeVideo, $dbVideo->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteVideo()
    {
        $video = $this->makeVideo();
        $resp = $this->videoRepo->delete($video->id);
        $this->assertTrue($resp);
        $this->assertNull(Video::find($video->id), 'Video should not exist in DB');
    }
}
