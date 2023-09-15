<?php

namespace App\Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Service\Messages;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Request\MessageListRequest;

class MessagesTest extends TestCase
{

    private Filesystem $filesystem;
    private ValidatorInterface $validator;
    private MessageListRequest $messageListRequest;
    private Messages $messages;

    public function setUp(): void
    {
        $this->filesystem = new Filesystem();
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->messageListRequest = new MessageListRequest();

        $this->messages = new Messages($this->filesystem, $this->validator, $this->messageListRequest);

        $temporaryTestDirectory = $this->messages->fileDirectory . 'messages_test/';
        $this->messages->fileDirectory = $temporaryTestDirectory;

    }

    public function testCreateNewWithMessage()
    {
        $content = ['message' => 'Test message'];
        $uuid = $this->messages->createNew($content);
        $this->assertNotEmpty($uuid);
        $filePath = $this->messages->fileDirectory . $uuid . $this->messages::FILE_NAME_SUFFIX;
        $this->assertFileExists($filePath);

        $fileContents = file_get_contents($filePath);
        $decodedMessage = json_decode($fileContents, true);

        $this->assertEquals($uuid, $decodedMessage['uuid']);
        $this->assertEquals($content['message'], $decodedMessage['content']);
        $this->assertArrayHasKey('createdAt', $decodedMessage);
    }


    public function testCreateNewWithMoutessage()
    {
        $uuid = $this->messages->createNew(null);

        $this->assertNotEmpty($uuid);

        $filePath = $this->messages->fileDirectory . $uuid . $this->messages::FILE_NAME_SUFFIX;
        $this->assertFileExists($filePath);

        $fileContents = file_get_contents($filePath);
        $decodedMessage = json_decode($fileContents, true);

        $this->assertEquals($uuid, $decodedMessage['uuid']);
        $this->assertEquals(null, $decodedMessage['content']);
        $this->assertArrayHasKey('createdAt', $decodedMessage);
    }

    public function testFindByUuid()
    {
        $content = ['message' => 'This is a test message'];
        $uuid = $this->messages->createNew($content);

        $foundMessage = $this->messages->findByUuid($uuid);

        $this->assertNotNull($foundMessage);
        $this->assertEquals($uuid, $foundMessage->uuid);
    }

    public function testFindByUuidNotFound()
    {
        $uuid = '3dd4eeee-9d68-477e-9c39-cd6384eeef23';

        $result = $this->messages->findByUuid($uuid);

        $this->assertEquals('no data for provided uuid', $result);
    }

    public function testListMessages()
    {
        $this->filesystem->remove($this->messages->fileDirectory);

        $content1 = ['message' => 'Message 1'];
        $content2 = ['message' => 'Message 2'];
        $this->messages->createNew($content1);
        $this->messages->createNew($content2);

        $messageList = $this->messages->list('date', 'asc');

        $this->assertCount(2, $messageList);
    }

    public function testListMessagesOrdering()
    {
        $this->filesystem->remove($this->messages->fileDirectory);

        $content1 = ['message' => 'Message 1'];
        $content2 = ['message' => 'Message 2'];
        $this->messages->createNew($content1);
        sleep(1);
        $this->messages->createNew($content2);

        $messageList = $this->messages->list('date', 'desc');

        $this->assertLessThan(strtotime($messageList[0]['created_at']), strtotime($messageList[1]['created_at']));

        $messageList = $this->messages->list('date', 'asc');

        $this->assertGreaterThan(strtotime($messageList[0]['created_at']), strtotime($messageList[1]['created_at']));

    }


    public function tearDown(): void
    {
        $this->filesystem->remove($this->messages->fileDirectory);
    }


}
