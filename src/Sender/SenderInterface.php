<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 14.12.2017
 * Time: 22:36
 */

namespace Jetifier\Sender;


use Jetifier\Exceptions\JetifierException;
use Jetifier\Message;

interface SenderInterface
{
    /**
     * @param string $url
     * @param Message $message
     * @param string $apiKey
     * @throws  JetifierException
     * @return array
     */
    public function send(string $url, Message $message, string $apiKey): array;
}