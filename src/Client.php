<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 14.12.2017
 * Time: 23:02
 */

namespace Jetifier;


use Jetifier\Constants\SelfConfig;
use Jetifier\Sender\Curl;
use Jetifier\Sender\SenderInterface;

class Client
{
    private $url = SelfConfig::API_URL;
    /**
     * @var SenderInterface
     */
    private $sender;
    private $apiKey;

    /**
     * Client constructor.
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->sender = new Curl();
    }

    /**
     * @param string $url
     * @return Client
     */
    public function setUrl(string $url): Client
    {
        $this->url = $url;
        return $this;
    }

    /**
     * @param SenderInterface $sender
     * @return Client
     */
    public function setSender(SenderInterface $sender): Client
    {
        $this->sender = $sender;
        return $this;
    }

    /**
     * @param Message $message
     * @return array
     * @throws \Jetifier\Exceptions\JetifierException
     */
    public function send(Message $message):array{
       return $this->sender->send(
            $this->url,
            $message,
            $this->apiKey
        );
    }


}