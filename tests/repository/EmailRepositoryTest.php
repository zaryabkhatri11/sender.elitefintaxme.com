<?php

namespace Tests\Repository;

use \App;
use Tests\ApiTestTrait;
use Tests\TestCase;
use \Tests\Traits\MakeEmailTrait;
use App\Models\Email;
use App\Repositories\Admin\EmailRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EmailRepositoryTest extends TestCase
{
    use MakeEmailTrait, ApiTestTrait, DatabaseTransactions;

    /**
     * @var EmailRepository
     */
    protected $emailRepo;

    public function setUp()
    {
        parent::setUp();
        $this->emailRepo = App::make(EmailRepository::class);
    }

    /**
     * @test create
     */
    public function testCreateEmail()
    {
        $email = $this->fakeEmailData();
        $createdEmail = $this->emailRepo->create($email);
        $createdEmail = $createdEmail->toArray();
        $this->assertArrayHasKey('id', $createdEmail);
        $this->assertNotNull($createdEmail['id'], 'Created Email must have id specified');
        $this->assertNotNull(Email::find($createdEmail['id']), 'Email with given id must be in DB');
        $this->assertModelData($email, $createdEmail);
    }

    /**
     * @test read
     */
    public function testReadEmail()
    {
        $email = $this->makeEmail();
        $dbEmail = $this->emailRepo->find($email->id);
        $dbEmail = $dbEmail->toArray();
        $this->assertModelData($email->toArray(), $dbEmail);
    }

    /**
     * @test update
     */
    public function testUpdateEmail()
    {
        $email = $this->makeEmail();
        $fakeEmail = $this->fakeEmailData();
        $updatedEmail = $this->emailRepo->update($fakeEmail, $email->id);
        $this->assertModelData($fakeEmail, $updatedEmail->toArray());
        $dbEmail = $this->emailRepo->find($email->id);
        $this->assertModelData($fakeEmail, $dbEmail->toArray());
    }

    /**
     * @test delete
     */
    public function testDeleteEmail()
    {
        $email = $this->makeEmail();
        $resp = $this->emailRepo->delete($email->id);
        $this->assertTrue($resp);
        $this->assertNull(Email::find($email->id), 'Email should not exist in DB');
    }
}
