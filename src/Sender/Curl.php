<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 14.12.2017
 * Time: 22:48
 */

namespace Jetifier\Sender;


use Jetifier\Exceptions\JetifierException;
use Jetifier\Message;

class Curl implements SenderInterface
{


    /**
     * Curl constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param string $url
     * @param Message $message
     * @param string $apiKey
     * @return array
     * @throws JetifierException
     */
    public function send(string $url, Message $message, string $apiKey): array
    {
        $handle = $this->init($url);
        curl_setopt_array($handle, $this->getParams($message,$apiKey));
        $result = curl_exec($handle);
        $httpCode= curl_getinfo($handle, CURLINFO_HTTP_CODE);
        $headerSize = curl_getinfo($handle, CURLINFO_HEADER_SIZE);
        curl_close($handle);

        if ($result === false) {
            throw  new JetifierException('Curl failure');
        }else if($httpCode !== 200){
            throw  new JetifierException('HTTP code: '.$httpCode);
        }

        return json_decode(substr($result, $headerSize), true);
    }

    private function init(string $url)
    {
        return curl_init($url);
    }

    private function getParams(Message $message, string  $apiKey): array
    {
        return [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($message),
            CURLOPT_HTTPHEADER => [
                'Authorization: key=' . $apiKey,
                'Content-Type: application/json'
            ],
            CURLINFO_HEADER_OUT => false,
            CURLOPT_HEADER => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true
        ];
    }

}