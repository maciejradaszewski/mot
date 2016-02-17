<?php

namespace DvsaCommon\Input\AuthorisedExaminerPrincipal;

use DvsaCommon\Validator\DateOfBirthValidator;
use Zend\InputFilter\Input;
use Zend\Validator\NotEmpty;
use Zend\Validator\Date;
use Zend\Validator\Callback;
use DvsaCommon\Date\DateTimeApiFormat;

class DateOfBirthInput extends Input
{
    const FIELD = 'dateOfBirth';
    const MSG_OVER_99_YEARS =  "must be less than 99 years ago";

    public function __construct($name = null)
    {
        parent::__construct(self::FIELD);

        $emptyValidator = (new NotEmpty())
            ->setMessage(DateOfBirthValidator::ERR_MSG_IS_EMPTY,DateOfBirthValidator::IS_EMPTY);

        $dobValidator = new DateOfBirthValidator();
        $dobValidator->setDateInThePast(new \DateTime('-99 years'));
        $dobValidator->setMessage(self::MSG_OVER_99_YEARS, DateOfBirthValidator::IS_OVER100);

        $this
            ->setRequired(true)
            ->getValidatorChain()
            ->attach($emptyValidator)
            ->attach($dobValidator)
        ;
    }
}
