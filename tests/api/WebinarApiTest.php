<?php

namespace Tests\Api;

use Tests\ApiTestTrait;
use Tests\TestCase;
use Tests\Traits\MakeWebinarTrait;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class WebinarApiTest extends TestCase
{
    use MakeWebinarTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateWebinar()
    {
        $webinar = $this->fakeWebinarData();
        $this->json('POST', '/api/v1/webinars', $webinar);

        $this->assertApiResponse($webinar);
    }

    /**
     * @test
     */
    public function testReadWebinar()
    {
        $webinar = $this->makeWebinar();
        $this->json('GET', '/api/v1/webinars/'.$webinar->id);

        $this->assertApiResponse($webinar->toArray());
    }

    /**
     * @test
     */
    public function testUpdateWebinar()
    {
        $webinar = $this->makeWebinar();
        $editedWebinar = $this->fakeWebinarData();

        $this->json('PUT', '/api/v1/webinars/'.$webinar->id, $editedWebinar);

        $this->assertApiResponse($editedWebinar);
    }

    /**
     * @test
     */
    public function testDeleteWebinar()
    {
        $webinar = $this->makeWebinar();
        $this->json('DELETE', '/api/v1/webinars/'.$webinar->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/webinars/'.$webinar->id);

        $this->assertResponseStatus(404);
    }
}
