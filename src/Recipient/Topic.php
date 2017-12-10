<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 10.12.2017
 * Time: 12:03
 */

namespace Jetifier\Recipient;


use Jetifier\Exceptions\BadRecipientIdentifierException;

/**
 * Class Topic
 * @package Jetifier\Recipient
 */
class Topic implements RecipientInterface
{

    private static $formatRegex = '/[a-zA-Z0-9-_.~%]+/';
    private static $prefix = '/topics/';
    private $topic;

    /**
     * Topic constructor.
     * @param string $topic Topic in format maches "[a-zA-Z0-9-_.~%]+" regex
     */
    public function __construct(string $topic)
    {
        $this->topic = $topic;
    }

    /**
     * @return string
     * @throws BadRecipientIdentifierException
     */
    public function getIdentifier(): string
    {

        return self::$prefix . $this->getTopic();
    }

    /**
     * @return string
     * @throws BadRecipientIdentifierException
     */
    public function getTopic(): string
    {
        if (empty($this->topic)) {
            throw new BadRecipientIdentifierException('Empty topic');
        }
        if (!$this->testFormat()) {
            throw new BadRecipientIdentifierException('Bad topic format');
        }

        return $this->topic;
    }

    private function testFormat(): bool
    {
        $out = [];
        preg_match(self::$formatRegex, $this->topic, $out);
        return ($out[0] ?? null) === $this->topic;
    }
}