<?php
/**
 * Created by PhpStorm.
 * User: pk
 * Date: 10.12.2017
 * Time: 11:27
 */

namespace Jetifier\Recipient;


use Jetifier\Exceptions\BadRecipientIdentifierException;

interface RecipientInterface
{
    /**
     * @return string
     * @throws BadRecipientIdentifierException
     */
    public function getIdentifier(): string;
}