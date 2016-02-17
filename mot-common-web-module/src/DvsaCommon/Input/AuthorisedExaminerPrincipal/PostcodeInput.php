<?php

namespace DvsaCommon\Input\AuthorisedExaminerPrincipal;

use Zend\InputFilter\Input;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Validator\Date;

class PostcodeInput extends Input
{
    const FIELD = 'postcode';
    const MSG_TOO_LONG = "must be %max% characters or less";
    const MAX_LENGTH = 10;

    public function __construct($name = null)
    {
        parent::__construct(self::FIELD);

        $lengthValidator = (new StringLength())
            ->setMax(self::MAX_LENGTH)
            ->setMessage(self::MSG_TOO_LONG, StringLength::TOO_LONG);

        $this
            ->setRequired(false)
            ->getValidatorChain()
            ->attach($lengthValidator);
    }
}