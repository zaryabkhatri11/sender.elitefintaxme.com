<?php

namespace Tests\Api;

use Tests\ApiTestTrait;
use Tests\TestCase;
use Tests\Traits\MakeVideoTrait;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VideoApiTest extends TestCase
{
    use MakeVideoTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateVideo()
    {
        $video = $this->fakeVideoData();
        $this->json('POST', '/api/v1/videos', $video);

        $this->assertApiResponse($video);
    }

    /**
     * @test
     */
    public function testReadVideo()
    {
        $video = $this->makeVideo();
        $this->json('GET', '/api/v1/videos/'.$video->id);

        $this->assertApiResponse($video->toArray());
    }

    /**
     * @test
     */
    public function testUpdateVideo()
    {
        $video = $this->makeVideo();
        $editedVideo = $this->fakeVideoData();

        $this->json('PUT', '/api/v1/videos/'.$video->id, $editedVideo);

        $this->assertApiResponse($editedVideo);
    }

    /**
     * @test
     */
    public function testDeleteVideo()
    {
        $video = $this->makeVideo();
        $this->json('DELETE', '/api/v1/videos/'.$video->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/videos/'.$video->id);

        $this->assertResponseStatus(404);
    }
}
