<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 14.12.2017
 * Time: 21:12
 */

namespace Jetifier;


use Jetifier\Constants\Priority;
use Jetifier\Exceptions\JetifierException;
use Jetifier\Payload\Data;
use Jetifier\Payload\Notification;
use Jetifier\Recipient\RecipientInterface;

class Message implements \JsonSerializable
{

    /**
     * @var Data
     */
    private $data;
    /**
     * @var Notification
     */
    private $notification;
    /**
     * @var RecipientInterface
     */
    private $recipient;

    private $simpleData = [
        'priority' => Priority::HIGH,
        'collapse_key' => null,
        'content_available' => null,
        'mutable_content' => null,
        'time_to_live' => null,
        'restricted_package_name' => null,
        'dry_run' => false
    ];


    /**
     * Sets the priority of the message. Valid values are "normal" and "high." On iOS,
     * these correspond to APNs priorities 5 and 10.
     *
     * By default, notification messages are sent with high priority, and data messages are sent with normal priority.
     * Normal priority optimizes the client app's battery consumption and should be used unless immediate
     * delivery is required. For messages with normal priority, the app may receive the message with unspecified delay.
     *
     * When a message is sent with high priority, it is sent immediately, and the app can wake a sleeping device
     * and open a network connection to your server.
     *
     * @param string $priority
     * @return Message
     * @throws JetifierException
     */
    public function setPriority(string $priority): Message
    {
        if (!\defined(Priority::class . '::' . $priority)) {
            throw  new JetifierException("Priority must be property of Constants \ Priority class");
        }
        $this->simpleData['priority'] = $priority;

        return $this;
    }

    /**
     * @param Notification $notification
     * @return Message
     */
    public function setNotification(Notification $notification): Message
    {
        $this->notification = $notification;
        return $this;
    }

    /**
     * @param Data $data
     * @return Message
     */
    public function setData(Data $data): Message
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param RecipientInterface $recipient
     * @return Message
     */
    public function setRecipient(RecipientInterface $recipient): Message
    {
        $this->recipient = $recipient;
        return $this;
    }

    /**
     * This parameter identifies a group of messages (e.g., with collapse_key: "Updates Available")
     * that can be collapsed, so that only the last message gets sent when delivery can be resumed.
     * This is intended to avoid sending too many of the same messages when the device comes
     * back online or becomes active.
     *
     * Note that there is no guarantee of the order in which messages get sent.
     *
     * Note: A maximum of 4 different collapse keys is allowed at any given time. This means a FCM connection server
     * can simultaneously store 4 different messages per client app. If you exceed this number, there is no guarantee
     * which 4 collapse keys the FCM connection server will keep.
     *
     * @param string $collapseKey
     * @return Message
     */
    public function setCollapseKey($collapseKey): Message
    {
        $this->simpleData['collapse_key'] = $collapseKey;

        return $this;
    }

    /**
     * On iOS, use this field to represent content-available in the APNs payload. When a notification or message
     * is sent and this is set to true, an inactive client app is awoken, and the message is sent through APNs
     * as a silent notification and not through the FCM connection server. Note that silent notifications in APNs
     * are not guaranteed to be delivered, and can depend on factors such as the user turning on Low Power Mode,
     * force quitting the app, etc. On Android, data messages wake the app by default.
     * On Chrome, currently not supported.
     *
     * @param bool $contentAvailable
     * @return Message
     */
    public function setContentAvailable(bool $contentAvailable): Message
    {
        $this->simpleData['content_available'] = $contentAvailable;
        return $this;
    }

    /**
     * Currently for iOS 10+ devices only. On iOS, use this field to represent mutable-content in the APNs payload.
     * When a notification is sent and this is set to true, the content of the notification can be modified before it
     * is displayed, using a Notification Service app extension. This parameter will be ignored for Android and web.
     *
     * @param bool $mutableContent
     * @return Message
     */
    public function setMutableContent(bool $mutableContent): Message
    {
        $this->simpleData['mutable_content'] = $mutableContent;
        return $this;
    }

    /**
     * This parameter specifies how long (in seconds) the message should be kept in FCM storage if
     * the device is offline. The maximum time to live supported is 4 weeks, and the default value is 4 weeks.
     *
     * The value must be a duration from 0 to 2,419,200 seconds (28 days)
     * @param int $timeToLive
     * @return Message
     * @throws \Jetifier\Exceptions\JetifierException
     */
    public function setTimeToLive(int $timeToLive): Message
    {
        if ($timeToLive > 2419200) {
            throw new JetifierException('TTL must be smaller than 28 days');
        }

        $this->simpleData['time_to_live'] = $timeToLive;
        return $this;
    }

    /**
     * This parameter specifies the package name of the application where the registration tokens must
     * match in order to receive the message.
     * @param string $restrictedPackageName
     * @return Message
     */
    public function setRestrictedPackageName(string $restrictedPackageName): Message
    {
        $this->simpleData['restricted_package_name'] = $restrictedPackageName;
        return $this;
    }

    /**
     * This parameter, when set to true, allows developers to test a request without actually sending a message.
     *
     * @param bool $dryRun
     * @return Message
     */
    public function setDryRun(bool $dryRun): Message
    {
        $this->simpleData['dry_run'] = $dryRun;
        return $this;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     * @throws \Jetifier\Exceptions\JetifierException
     */
    public function jsonSerialize()
    {
        $message = $this->getFilteredSimpleData();

        if (empty($this->recipient)) {
            throw new JetifierException('Message must have recipient');
        }

        $message [$this->recipient->getKey()] = $this->recipient->getIdentifier();


        if ($this->notification !== null) {
            $message['notification'] = $this->notification->jsonSerialize();
        }

        if ($this->data !== null) {
            $message['data'] = $this->data->jsonSerialize();
        }
        return $message;
    }

    private function getFilteredSimpleData()
    {
        return array_filter($this->simpleData, function ($value) {
            return $value !== null;
        });
    }


}