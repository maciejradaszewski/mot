<?php

namespace DvsaCommon\Input\AuthorisedExaminerPrincipal;

use Zend\InputFilter\Input;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

class AddressLine1Input extends Input
{
    const FIELD = 'addressLine1';
    const MSG_EMPTY = 'you must enter the first line of the address';
    const MSG_TOO_LONG = "must be %max% characters or less";
    const MAX_LENGTH = 50;

    public function __construct($name = null)
    {
        parent::__construct(self::FIELD);

        $emptyValidator = (new NotEmpty())
            ->setMessage(self::MSG_EMPTY, NotEmpty::IS_EMPTY);

        $lengthValidator = (new StringLength())
            ->setMax(self::MAX_LENGTH)
            ->setMessage(self::MSG_TOO_LONG, StringLength::TOO_LONG);

        $this
            ->setRequired(true)
            ->getValidatorChain()
            ->attach($emptyValidator)
            ->attach($lengthValidator);
    }
}