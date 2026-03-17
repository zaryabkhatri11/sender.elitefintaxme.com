<?php

namespace Tests\Api;

use Tests\ApiTestTrait;
use Tests\TestCase;
use Tests\Traits\MakeMessagesLogTrait;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MessagesLogApiTest extends TestCase
{
    use MakeMessagesLogTrait, ApiTestTrait, WithoutMiddleware, DatabaseTransactions;

    /**
     * @test
     */
    public function testCreateMessagesLog()
    {
        $messagesLog = $this->fakeMessagesLogData();
        $this->json('POST', '/api/v1/messages-logs', $messagesLog);

        $this->assertApiResponse($messagesLog);
    }

    /**
     * @test
     */
    public function testReadMessagesLog()
    {
        $messagesLog = $this->makeMessagesLog();
        $this->json('GET', '/api/v1/messages-logs/'.$messagesLog->id);

        $this->assertApiResponse($messagesLog->toArray());
    }

    /**
     * @test
     */
    public function testUpdateMessagesLog()
    {
        $messagesLog = $this->makeMessagesLog();
        $editedMessagesLog = $this->fakeMessagesLogData();

        $this->json('PUT', '/api/v1/messages-logs/'.$messagesLog->id, $editedMessagesLog);

        $this->assertApiResponse($editedMessagesLog);
    }

    /**
     * @test
     */
    public function testDeleteMessagesLog()
    {
        $messagesLog = $this->makeMessagesLog();
        $this->json('DELETE', '/api/v1/messages-logs/'.$messagesLog->id);

        $this->assertApiSuccess();
        $this->json('GET', '/api/v1/messages-logs/'.$messagesLog->id);

        $this->assertResponseStatus(404);
    }
}
