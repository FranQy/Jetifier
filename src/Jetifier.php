<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 16.12.2017
 * Time: 17:08
 */

namespace Jetifier;


use Jetifier\Exceptions\JetifierException;
use Jetifier\Payload\Notification;
use Jetifier\Recipient\Device;
use Jetifier\Recipient\Topic;
use Jetifier\Sender\SenderInterface;

class Jetifier
{
    private $client;
    private $message;
    private $notification;
    private $recipient;


    /**
     * Jetifier constructor.
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->client = new Client($apiKey);
        $this->message = new Message();
        $this->notification = new Notification();
        $this->recipient;
    }

    /**
     * @param string $title
     * @return Jetifier
     */
    public function setTitle(string $title): Jetifier
    {
        $this->notification->setTitle($title);
        return $this;
    }

    /**
     * @param string $body
     * @return Jetifier
     */
    public function setBody(string $body): Jetifier
    {
        $this->notification->setBody($body);
        return $this;
    }

    /**
     * Set Recipient as single device's registration token
     *
     * @param string $deviceToken
     * @return Jetifier
     * @throws \Jetifier\Exceptions\JetifierException
     */
    public function setDeviceToken(string $deviceToken): Jetifier
    {
        $this->checkRecipientAlreadySets();
        $this->recipient = new Device($deviceToken);
        return $this;
    }

    /**
     * @throws JetifierException
     */
    private function checkRecipientAlreadySets(): void
    {
        if ($this->recipient !== null) {
            throw new JetifierException('Recipient is already set.');
        }

    }

    /**
     * Set Recipient as single topic
     * @param string $topic
     * @return Jetifier
     * @throws \Jetifier\Exceptions\JetifierException
     */
    public function setTopic(string $topic): Jetifier
    {
        $this->checkRecipientAlreadySets();
        $this->recipient = new Topic($topic);
        return $this;
    }

    /**
     * @param SenderInterface $sender
     * @return Jetifier
     */
    public function setSender(SenderInterface $sender): Jetifier
    {
        $this->client->setSender($sender);

        return $this;

    }

    /**
     * @return array
     * @throws \Jetifier\Exceptions\JetifierException
     */
    public function send(): array
    {
        $this->checkRecipientSupply();

        $this->message
            ->setNotification($this->notification)
            ->setRecipient($this->recipient);
        return $this->client->send($this->message);
    }

    /**
     * @throws JetifierException
     */
    private function checkRecipientSupply(): void
    {
        if($this->recipient === null){
          throw new JetifierException('Recipient must be set');
        }
    }

}