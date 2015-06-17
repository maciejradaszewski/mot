<?php

namespace DvsaCommon\Validator;

use Zend\Validator\StringLength;

/**
 * UsernameValidator validates User|Person usernames.
 */
class UsernameValidator extends StringLength
{
    /**
     * Custom validation messages, overriding those provided by the ZF2 validator class.
     *
     * @var array
     */
    protected $messageTemplates = [
        self::INVALID   => "Invalid type given. String expected.",
        self::TOO_SHORT => "Username must be longer than %min% characters.",
        self::TOO_LONG  => "Username must be less than %max% characters long.",
    ];
}
