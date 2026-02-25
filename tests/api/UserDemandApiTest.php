<?php

namespace Tests\Api;

use Tests\ApiTestTrait;
use Tests\TestCase;
use Tests\Traits\MakeUserDemandTrait;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserDemandApiTest extends TestCase
{
    use MakeUserDemandTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateUserDemand()
    {
        $userDemand = $this->fakeUserDemandData();
        $this->json('POST', '/api/v1/user-demands', $userDemand);

        $this->assertApiResponse($userDemand);
    }

    /**
     * @test
     */
    public function testReadUserDemand()
    {
        $userDemand = $this->makeUserDemand();
        $this->json('GET', '/api/v1/user-demands/'.$userDemand->id);

        $this->assertApiResponse($userDemand->toArray());
    }

    /**
     * @test
     */
    public function testUpdateUserDemand()
    {
        $userDemand = $this->makeUserDemand();
        $editedUserDemand = $this->fakeUserDemandData();

        $this->json('PUT', '/api/v1/user-demands/'.$userDemand->id, $editedUserDemand);

        $this->assertApiResponse($editedUserDemand);
    }

    /**
     * @test
     */
    public function testDeleteUserDemand()
    {
        $userDemand = $this->makeUserDemand();
        $this->json('DELETE', '/api/v1/user-demands/'.$userDemand->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/user-demands/'.$userDemand->id);

        $this->assertResponseStatus(404);
    }
}
