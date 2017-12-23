<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 10.12.2017
 * Time: 15:19
 */

namespace Jetifier\Tests\Content;

use Jetifier\Payload\Notification;
use PHPUnit\Framework\MockObject\RuntimeException;
use PHPUnit\Framework\TestCase;

class NotificationTest extends TestCase
{

    public function testNotificationSerialization()
    {
        $notification = new Notification('title');
        $notification->setBody('body');
        $expect = ['title' => 'title', 'body' => 'body'];

        $this->assertEquals($expect, $notification->jsonSerialize());
    }

    public function testEmptyNotificationSerialization()
    {
        $notification = new Notification(null);
        $this->assertEquals([], $notification->jsonSerialize());
    }

    public function testSetColor()
    {
        $notification = new Notification(null);
        $notification->setColor('#09afAF');
        $this->assertEquals(['color' => '#09afAF'], $notification->jsonSerialize());
    }

    public function testSetColorEmptyException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $notification = new Notification(null);
        $notification->setColor('');
    }

    public function testSetColorTooShortException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $notification = new Notification(null);
        $notification->setColor('#123');

    }

    public function testSetColorBadValueException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $notification = new Notification(null);
        $notification->setColor('#1234g7');

    }

    public function testSetBadge()
    {
        $notification = new Notification(null);
        $notification->setBadge(5);
        $this->assertEquals(['badge' => 5], $notification->jsonSerialize());
    }

    public function testSetBadgeZero()
    {
        $notification = new Notification(null);
        $notification->setBadge(0);
        $this->assertEquals(['badge' => 0], $notification->jsonSerialize());
    }


    public function testSetBadgeException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $notification = new Notification(null);
        $notification->setBadge(-1);
    }

    public function testSetTag()
    {
        $notification = new Notification(null);
        $notification->setTag('tagName');
        $this->assertEquals(['tag' => 'tagName'], $notification->jsonSerialize());
    }

    public function testSetIcon()
    {
        $notification = new Notification(null);
        $notification->setIcon('iconName');
        $this->assertEquals(['icon' => 'iconName'], $notification->jsonSerialize());
    }

    public function testSetClickAction()
    {
        $notification = new Notification(null);
        $notification->setClickAction('ca');
        $this->assertEquals(['click_action' => 'ca'], $notification->jsonSerialize());
    }

    public function testSetSound()
    {
        $notification = new Notification(null);
        $notification->setSound('soundName');
        $this->assertEquals(['sound' => 'soundName'], $notification->jsonSerialize());
    }



    public function testSetCustomPropertyException()
    {
        $this->expectException(\RuntimeException::class);
        $notification = new Notification(null);
        $notification->test ='a';
    }


}

