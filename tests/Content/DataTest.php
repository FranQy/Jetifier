<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 10.12.2017
 * Time: 16:07
 */

namespace Jetifier\Tests\Content;

use Jetifier\Content\Data;
use PHPUnit\Framework\TestCase;

class DataTest extends TestCase
{

    public function testDataSerialize()
    {
        $data = new Data();
        $data->add('a', 'b')
            ->add('c', 'd');
        $expects = ['a' => 'b', 'c' => 'd'];

        $this->assertEquals($expects, $data->jsonSerialize());
    }

    public function testSameKeyException()
    {
        $this->expectException(\InvalidArgumentException::class);
        $data = new Data();
        $data->add('abc', 'aaa');
        $data->add('abc', 'bbb');
    }


}
