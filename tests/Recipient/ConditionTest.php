<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 10.12.2017
 * Time: 12:53
 */

namespace Jetifier\Tests\Recipient;

use Jetifier\Exceptions\BadRecipientIdentifierException;
use Jetifier\Recipient\Condition;
use Jetifier\Recipient\Topic;
use PHPUnit\Framework\TestCase;

class ConditionTest extends TestCase
{

    public function testInitialCondition()
    {
        $topic = 'topic';
        $formatted = "'$topic' in topics";
        $condition = new Condition(new Topic($topic));
        $this->assertEquals($formatted, $condition->getIdentifier());
    }

    public function testGetKey()
    {
        $key = 'condition';
        $device = new Condition(new Topic('topic'));
        $this->assertEquals($key, $device->getKey());
    }

    public function testAddAndTopic()
    {

        $formattedCondition = "'a' in topics && 'b' in topics";
        $condition = new Condition(new Topic('a'));
        $condition->andTopic(new Topic('b'));

        $this->assertEquals($formattedCondition, $condition->getIdentifier());
    }

    public function testAddMoreThanThreeException()
    {

        $this->expectException(BadRecipientIdentifierException::class);

        $condition = new Condition(new Topic('a'));
        $condition->andTopic(new Topic('b'));
        $condition->andTopic(new Topic('c'));
        $condition->andTopic(new Topic('d'));
        $condition->getIdentifier();
    }


    public function testAddOrTopic()
    {
        $formattedCondition = "'a' in topics || 'b' in topics";

        $condition = new Condition(new Topic('a'));
        $condition->orTopic(new Topic('b'));

        $this->assertEquals($formattedCondition, $condition->getIdentifier());
    }

    public function testAddAndCondition()
    {
        $formattedCondition = "'a' in topics && ('b' in topics || 'c' in topics)";

        $condition = new Condition(new Topic('a'));
        $subCondition = new Condition(new Topic('b'));
        $subCondition->orTopic(new Topic('c'));

        $condition->andCondition($subCondition);

        $this->assertEquals($formattedCondition, $condition->getIdentifier());
    }

    public function testAddOrCondition()
    {
        $formattedCondition = "'a' in topics || ('b' in topics || 'c' in topics)";

        $condition = new Condition(new Topic('a'));
        $subCondition = new Condition(new Topic('b'));
        $subCondition->orTopic(new Topic('c'));

        $condition->orCondition($subCondition);

        $this->assertEquals($formattedCondition, $condition->getIdentifier());
    }

    public function testAddMoreThanThreeWithSubConditionException()
    {

        $this->expectException(BadRecipientIdentifierException::class);

        $condition = new Condition(new Topic('a'));
        $subCondition = new Condition(new Topic('b'));
        $condition->andTopic(new Topic('c'));
        $subCondition->orTopic(new Topic('d'));
        $condition->orCondition($subCondition);

        $condition->getIdentifier();

    }

}
