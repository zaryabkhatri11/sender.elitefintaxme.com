<?php

namespace Tests\Repository;

use \App;
use Tests\ApiTestTrait;
use Tests\TestCase;
use \Tests\Traits\MakeMessagesLogTrait;
use App\Models\MessagesLog;
use App\Repositories\Admin\MessagesLogRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MessagesLogRepositoryTest extends TestCase
{
    use MakeMessagesLogTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var MessagesLogRepository
     */
    protected $messagesLogRepo;

    public function setUp()
    {
        parent::setUp();
        $this->messagesLogRepo = App::make(MessagesLogRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateMessagesLog()
    {
        $messagesLog = $this->fakeMessagesLogData();
        $createdMessagesLog = $this->messagesLogRepo->create($messagesLog);
        $createdMessagesLog = $createdMessagesLog->toArray();
        $this->assertArrayHasKey('id', $createdMessagesLog);
        $this->assertNotNull($createdMessagesLog['id'], 'Created MessagesLog must have id specified');
        $this->assertNotNull(MessagesLog::find($createdMessagesLog['id']), 'MessagesLog with given id must be in DB');
        $this->assertModelData($messagesLog, $createdMessagesLog);
    }

    /**
     * @test read
     */
    public function testReadMessagesLog()
    {
        $messagesLog = $this->makeMessagesLog();
        $dbMessagesLog = $this->messagesLogRepo->find($messagesLog->id);
        $dbMessagesLog = $dbMessagesLog->toArray();
        $this->assertModelData($messagesLog->toArray(), $dbMessagesLog);
    }

    /**
     * @test update
     */
    public function testUpdateMessagesLog()
    {
        $messagesLog = $this->makeMessagesLog();
        $fakeMessagesLog = $this->fakeMessagesLogData();
        $updatedMessagesLog = $this->messagesLogRepo->update($fakeMessagesLog, $messagesLog->id);
        $this->assertModelData($fakeMessagesLog, $updatedMessagesLog->toArray());
        $dbMessagesLog = $this->messagesLogRepo->find($messagesLog->id);
        $this->assertModelData($fakeMessagesLog, $dbMessagesLog->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteMessagesLog()
    {
        $messagesLog = $this->makeMessagesLog();
        $resp = $this->messagesLogRepo->delete($messagesLog->id);
        $this->assertTrue($resp);
        $this->assertNull(MessagesLog::find($messagesLog->id), 'MessagesLog should not exist in DB');
    }
}
