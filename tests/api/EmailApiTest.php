<?php

namespace Tests\Api;

use Tests\ApiTestTrait;
use Tests\TestCase;
use Tests\Traits\MakeEmailTrait;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmailApiTest extends TestCase
{
    use MakeEmailTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateEmail()
    {
        $email = $this->fakeEmailData();
        $this->json('POST', '/api/v1/emails', $email);

        $this->assertApiResponse($email);
    }

    /**
     * @test
     */
    public function testReadEmail()
    {
        $email = $this->makeEmail();
        $this->json('GET', '/api/v1/emails/'.$email->id);

        $this->assertApiResponse($email->toArray());
    }

    /**
     * @test
     */
    public function testUpdateEmail()
    {
        $email = $this->makeEmail();
        $editedEmail = $this->fakeEmailData();

        $this->json('PUT', '/api/v1/emails/'.$email->id, $editedEmail);

        $this->assertApiResponse($editedEmail);
    }

    /**
     * @test
     */
    public function testDeleteEmail()
    {
        $email = $this->makeEmail();
        $this->json('DELETE', '/api/v1/emails/'.$email->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/emails/'.$email->id);

        $this->assertResponseStatus(404);
    }
}
