<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 10.12.2017
 * Time: 12:43
 */

namespace Jetifier\Tests\Recipient;

use Jetifier\Exceptions\BadRecipientIdentifierException;
use Jetifier\Recipient\Topic;
use PHPUnit\Framework\TestCase;

class TopicTest extends TestCase
{
    public function testGetIdentifier()
    {
        $identifier = 'ident';
        $device = new Topic($identifier);
        $this->assertEquals('/topics/' . $identifier, $device->getIdentifier());
    }

    public function testGetTopic()
    {
        $identifier = 'ident';
        $device = new Topic($identifier);
        $this->assertEquals($identifier, $device->getTopic());
    }

    public function testGetIdentifierEmptyException()
    {
        $device = new Topic('');
        $this->expectException(BadRecipientIdentifierException::class);
        $device->getIdentifier();
    }

    public function testGetIdentifierBadFormatException()
    {
        $device = new Topic('a b');
        $this->expectException(BadRecipientIdentifierException::class);
        $device->getIdentifier();
    }


    public function testGetIdentifierType()
    {
        $identifier = 1;
        $device = new Topic($identifier);
        $this->assertInternalType('string', $device->getIdentifier());
    }
}
