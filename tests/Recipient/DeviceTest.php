<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 10.12.2017
 * Time: 11:36
 */

namespace Jetifier\Tests\Recipient;

use Jetifier\Exceptions\BadRecipientIdentifierException;
use Jetifier\Recipient\Device;
use PHPUnit\Framework\TestCase;

class DeviceTest extends TestCase
{

    public function testGetIdentifier()
    {
        $identifier = 'ident';
        $device = new Device($identifier);
        $this->assertEquals($identifier, $device->getIdentifier());
    }

    public function testGetIdentifierException()
    {
        $device = new Device('');
        $this->expectException(BadRecipientIdentifierException::class);
        $device->getIdentifier();
    }


    public function testGetIdentifierType()
    {
        $identifier = 1;
        $device = new Device($identifier);
        $this->assertInternalType('string', $device->getIdentifier());
    }
}
