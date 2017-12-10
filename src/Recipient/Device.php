<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 10.12.2017
 * Time: 11:28
 */

namespace Jetifier\Recipient;


use Jetifier\Exceptions\BadRecipientIdentifierException;

class Device implements RecipientInterface
{

    /**
     *
     * @var String $deviceToken
     */
    private $deviceToken;

    /**
     * Device constructor.
     * @param String $deviceToken
     */
    public function __construct(string $deviceToken)
    {
        $this->deviceToken = $deviceToken;
    }


    /**
     * @return string
     * @throws BadRecipientIdentifierException
     */
    public function getIdentifier(): string
    {
        if (empty($this->deviceToken)) {
            throw new BadRecipientIdentifierException('Empty device identifier');
        }
        return $this->deviceToken;
    }
}