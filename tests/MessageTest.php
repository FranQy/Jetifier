<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 14.12.2017
 * Time: 21:17
 */

namespace Jetifier;

use Jetifier\Constants\Priority;
use Jetifier\Exceptions\JetifierException;
use Jetifier\Payload\Data;
use Jetifier\Payload\Notification;
use Jetifier\Recipient\Topic;
use PHPUnit\Framework\TestCase;

class MessageTest extends TestCase
{

    public function testSetPriorityBadValue()
    {
        $this->expectException(JetifierException::class);
        $message = new Message();
        $message->setPriority('ad');
    }

    public function testSetPriorityEmpty()
    {
        $this->expectException(JetifierException::class);
        $message = new Message();
        $message->setPriority('');
    }

    public function testSetTTLTooBig()
    {
        $this->expectException(JetifierException::class);
        $message = new Message();
        $message->setTimeToLive(999999999);
    }

    public function testSerializeMessage()
    {
        $message = new Message();
        $topic = new Topic('a');
        $data = new Data();
        $notification = new Notification();

        $data->add('a', 'aa')->add('b', 'bb');
        $notification->setTitle('title');
        $notification->setBody('body');

        $message->setRecipient($topic)
            ->setNotification($notification)
            ->setPriority(Priority::NORMAL)
            ->setDryRun(false)
            ->setRestrictedPackageName('package')
            ->setTimeToLive(120)
            ->setMutableContent(true)
            ->setContentAvailable(true)
            ->setCollapseKey('collapse')
            ->setData($data);

        $expect = [
            'priority' => Priority::NORMAL,
            'collapse_key'=>'collapse',
            'content_available'=>true,
            'mutable_content'=>true,
            'time_to_live'=>120,
            'restricted_package_name'=>'package',
            'dry_run' => false,
            'to' => $topic->getIdentifier(),

            'notification' =>$notification->jsonSerialize(),
            'data' => $data->jsonSerialize()
        ];
        $messageArray = $message->jsonSerialize();
       $this->assertEquals($expect,$messageArray);
    }

    public function testSerializeIncompleteMessage()
    {
        $this->expectException(JetifierException::class);
        $message = new Message();
        $message->jsonSerialize();
    }


}
