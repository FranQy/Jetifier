<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 10.12.2017
 * Time: 16:06
 */

namespace Jetifier\Payload;


class Data implements \JsonSerializable
{
    private $data;

    /**
     * @param string $key
     * @param string $value
     * @return Data
     * @throws \InvalidArgumentException
     */
    public function add(string $key, string $value): Data
    {

        $this->checkKey($key);
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * @param string $key
     * @throws \InvalidArgumentException
     */
    private function checkKey(string $key)
    {
        if (isset($this->data[$key])) {
            throw new \InvalidArgumentException("Data with that key already exists");
        }
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return $this->data;
    }
}