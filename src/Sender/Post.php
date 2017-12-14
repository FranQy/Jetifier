<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 14.12.2017
 * Time: 22:44
 */

namespace Jetifier\Sender;


use Jetifier\Exceptions\JetifierException;
use Jetifier\Message;

class Post implements SenderInterface
{

    /**
     * @param string $url
     * @param Message $message
     * @return array
     *
     * @throws \Jetifier\Exceptions\JetifierException
     */
    public function send(string $url, Message $message, string $apiKey): array
    {

        $options = array(
            'http' => array(
                'header' => ["Content-type: application/json",
                             "Authorization: key=$apiKey"],
                'method' => 'POST',
                'content' => json_encode($message)
            ),
        );
        $context = stream_context_create($options);

        $result = file_get_contents($url, false, $context);

        if ($result === false) {
            throw  new JetifierException('Post failure');
        }

        return json_decode($result, true);
    }


}