<?php

namespace DvsaCommon\InputFilter\Event;

use DvsaCommon\Enum\EventOutcomeCode;
use Zend\InputFilter\InputFilter;
use Zend\Validator\NotEmpty;
use Zend\Validator\Callback;

class OutcomeInputFilter extends InputFilter
{
    const FIELD_OUTCOME = 'outcomeCode';
    const FIELD_NOTES = 'notes';
    const MSG_TYPE_EMPTY = 'you must choose an outcome';
    const MSG_NOTES_MAX_CHARS = 'must be less than 250 chars';
    const FIELD_NOTES_MAX_LENGTH = 250;

    public function init()
    {
        $this->initValidatorForType(self::FIELD_OUTCOME);
        $this->initValidatorForNotes(self::FIELD_NOTES);
    }

    public function initValidatorForNotes($fieldName)
    {
        $eventNotesValidator = new Callback(function($value) {
            $length = strlen(trim($value));
            if ($length == 0 || $length > self::FIELD_NOTES_MAX_LENGTH) {
                return false;
            }
            return true;
        });

        $eventNotesValidator->setMessage(self::MSG_NOTES_MAX_CHARS);

        $input = [
            'name'       => $fieldName,
            'required'   => false,
            'validators' => [
                $eventNotesValidator
            ],
        ];

        $this->add($input);
    }

    public function initValidatorForType($fieldName)
    {
        $eventTypeValidator = new Callback(function($value) {
            if (! EventOutcomeCode::exists($value)) {
                return false;
            }
            return true;
        });
        $eventTypeValidator->setMessage('Type not recognised');

        $input = [
            'name'       => $fieldName,
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
}