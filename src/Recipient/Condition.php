<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 10.12.2017
 * Time: 12:47
 */

namespace Jetifier\Recipient;


use Jetifier\Exceptions\BadRecipientIdentifierException;

class Condition implements RecipientInterface
{

    private static $key = 'condition';
    private static $maxTopicsCount = 3;
    private $topicsCount = 0;
    private $condition = '';


    /**
     * Condition constructor.
     * @param Topic $topic
     * @throws \Jetifier\Exceptions\BadRecipientIdentifierException
     */
    public function __construct(Topic $topic)
    {
        $this->appendTopicToCondition('', $topic->getTopic());
        $this->incrementTopicsCounter(1);
    }

    private function appendTopicToCondition(string $operator, string $topic)
    {
        $this->condition .= " $operator '$topic' in topics";
    }

    private function incrementTopicsCounter(int $amount)
    {
        $this->topicsCount += $amount;
    }

    public function andTopic(Topic $topic)
    {
        $this->appendTopicToCondition('&&', $topic->getTopic());
        $this->incrementTopicsCounter(1);
    }

    public function orTopic(Topic $topic)
    {
        $this->appendTopicToCondition('||', $topic->getTopic());
        $this->incrementTopicsCounter(1);
    }

    public function andCondition(Condition $subCondition)
    {
        $this->appendCondition('&&', $subCondition->getIdentifier());
        $this->incrementTopicsCounter($subCondition->topicsCount);
    }

    private function appendCondition(string $operator, string $condition)
    {
        $this->condition .= " $operator ($condition)";
    }

    /**
     * @return string
     * @throws BadRecipientIdentifierException
     */
    public function getIdentifier(): string
    {
        $this->checkTopicsCount();
        return trim($this->condition);
    }

    /**
     * @throws BadRecipientIdentifierException
     */
    private function checkTopicsCount()
    {
        if ($this->topicsCount > self::$maxTopicsCount) {
            throw new BadRecipientIdentifierException('Too many topics in condition');
        }
    }

    public function orCondition(Condition $subCondition)
    {
        $this->appendCondition('||', $subCondition->getIdentifier());
        $this->incrementTopicsCounter($subCondition->topicsCount);
    }

    public function getKey(): string
    {
        return self::$key;
    }
}