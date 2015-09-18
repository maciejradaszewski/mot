<?php

namespace DvsaCommon\InputFilter\Event;

use DvsaCommon\Enum\EventTypeCode;
use DvsaCommon\Validator\RecordEventDateValidator;
use Zend\InputFilter\InputFilter;
use Zend\Validator\Callback;
use Zend\Validator\Date;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;

class RecordInputFilter extends InputFilter
{
    const FIELD_TYPE = 'eventType';
    const FIELD_DAY = 'day';
    const FIELD_MONTH = 'month';
    const FIELD_YEAR = 'year';
    const FIELD_DATE = 'date';

    const MSG_TYPE_EMPTY = 'you must choose an event';
    const MSG_TYPE_INVALID = 'you must choose an event';
    const MSG_DATE_EMPTY = 'you must enter a date';
    const MSG_DATE_INVALID = 'date is incorrect';

    public function init()
    {
        $this->initValidatorForType();
        $this->initValidatorForDate();
    }

    public function initValidatorForType()
    {
        // Create a callback validator that checks the code is one that we recognise in the enum
        $eventTypeValidator = new Callback(function($value) {
            if (! EventTypeCode::exists($value)) {
                return false;
            }
            return true;
        });
        // Set the message of the validator
        $eventTypeValidator->setMessage(self::MSG_TYPE_INVALID);

        $input = [
            'name'       => self::FIELD_TYPE,
            'required'   => true,
            'validators' => [
                [
                    'name'    => NotEmpty::class,
                    'options' => [
                        'message' => self::MSG_TYPE_EMPTY,
                    ],
                ],
                $eventTypeValidator,
            ],
        ];

        $this->add($input);
    }

    public function initValidatorForDate()
    {
        $input = [
            'name' => self::FIELD_DATE,
            'required' => true,
            'validators' => [
                [
                    'name' => NotEmpty::class,
                    'options' => [
                        'message' => self::MSG_DATE_EMPTY,
                    ],
                ],
                [
                    'name' => RecordEventDateValidator::class
                ]
            ]
        ];

        $this->add($input);
    }
}