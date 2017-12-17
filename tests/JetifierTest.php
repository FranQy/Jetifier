<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 16.12.2017
 * Time: 17:09
 */

namespace Jetifier;

use Jetifier\Exceptions\JetifierException;
use Jetifier\Sender\Post;
use Jetifier\Sender\SenderInterface;
use PHPUnit\Framework\TestCase;

class JetifierTest extends TestCase
{

    public function testSetTitle()
    {
        $jetifier = new Jetifier('');
        $this->assertInstanceOf(Jetifier::class, $jetifier->setTitle('a'));
    }

    public function testSetBody()
    {
        $jetifier = new Jetifier('');
        $this->assertInstanceOf(Jetifier::class, $jetifier->setBody('a'));
    }

    public function testSetDeviceId()
    {
        $jetifier = new Jetifier('');
        $this->assertInstanceOf(Jetifier::class, $jetifier->setDeviceToken('a'));
    }

    public function testSetDeviceIdAlreadySet()
    {
        $this->expectException(JetifierException::class);
        $jetifier = new Jetifier('');
        $jetifier->setDeviceToken('a');
        $jetifier->setDeviceToken('b');
    }

    public function testSetTopic()
    {
        $jetifier = new Jetifier('');
        $this->assertInstanceOf(Jetifier::class, $jetifier->setTopic('a'));
    }

    public function testSetTopicAlreadySet()
    {
        $this->expectException(JetifierException::class);
        $jetifier = new Jetifier('');
        $jetifier->setTopic('a');
        $jetifier->setTopic('b');
    }

    public function testSetRecipientMixed()
    {
        $this->expectException(JetifierException::class);
        $jetifier = new Jetifier('');
        $jetifier->setTopic('a');
        $jetifier->setDeviceToken('b');
    }

    public function testSetSender()
    {
        $jetifier = new Jetifier('');
        $this->assertInstanceOf(Jetifier::class, $jetifier->setSender(new Post()));
    }

    public function testSend()
    {
        $senderResponse = ['message_id' => -1];
        $sender = $this->createMock(SenderInterface::class);
        $sender->method('send')
            ->willReturn($senderResponse);

        $jetifier = new Jetifier('');
        $response = $jetifier->setSender($sender)
            ->setDeviceToken('id')
            ->setTitle('title')
            ->send();

        $this->assertEquals($senderResponse, $response);
    }

    public function testSendWithoutRespient()
    {
        $sender = $this->createMock(SenderInterface::class);
        $sender->method('send')
            ->willReturn(['message_id' => -1]);

        $this->expectException(JetifierException::class);

        $jetifier = new Jetifier('');
        $jetifier->setSender($sender)->send();
    }


}
