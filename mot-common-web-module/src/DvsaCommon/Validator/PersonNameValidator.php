<?php

namespace DvsaCommon\Validator;

use Zend\Validator\AbstractValidator;

class PersonNameValidator extends AbstractValidator
{
    const FIRST_NAME_KEY = 'firstName';
    const MIDDLE_NAME_KEY = 'middleName';
    const LAST_NAME_KEY = 'lastName';

    const FIELD_MAX_LENGTH = 45;
    const MSG_FIRST_NAME_IS_EMPTY = 'you must enter a first name';
    const MSG_LAST_NAME_IS_EMPTY = 'you must enter a last name';
    const MSG_FIRST_NAME_IS_TOO_LONG = 'must be 45 characters or less';
    const MSG_MIDDLE_NAME_IS_TOO_LONG = 'must be 45 characters or less ';
    const MSG_LAST_NAME_IS_TOO_LONG = 'must be 45 characters or less  ';


    /**
     * @var array
     */
    protected $messageTemplates = [
        self::FIRST_NAME_KEY => true,
        self::MIDDLE_NAME_KEY => true,
        self::LAST_NAME_KEY => true
    ];

    /**
     * @var array
     */
    protected $nameFieldLabels = [
        self::FIRST_NAME_KEY => 'First name',
        self::MIDDLE_NAME_KEY => 'Middle name',
        self::LAST_NAME_KEY => 'Last name',
    ];

    /**
     * @param array $nameData
     *
     * @return bool
     */
    public function isValid($nameData)
    {
        return $this->validate($nameData);
    }

    /**
     * @param $field
     *
     * @return string
     */
    public function getFieldLabel($field)
    {
        if (isset($this->nameFieldLabels[$field])) {
            return $this->nameFieldLabels[$field];
        }
    }

    /**
     * @param array $nameData
     *
     * @return bool
     */
    private function validate(array $nameData)
    {
        $firstNameValid = true;
        $middleNameValid = true;
        $lastNameValid = true;

        if (empty($nameData['firstName'])) {
            $this->setMessage(self::MSG_FIRST_NAME_IS_EMPTY, self::FIRST_NAME_KEY);
            $this->error(self::FIRST_NAME_KEY);
            $firstNameValid = false;
        }
        if (strlen($nameData['firstName']) > self::FIELD_MAX_LENGTH) {
            $this->setMessage(self::MSG_FIRST_NAME_IS_TOO_LONG, self::FIRST_NAME_KEY);
            $this->error(self::FIRST_NAME_KEY);
            $firstNameValid = false;
        }
        if (strlen($nameData['middleName']) > self::FIELD_MAX_LENGTH) {
            $this->setMessage(self::MSG_MIDDLE_NAME_IS_TOO_LONG, self::MIDDLE_NAME_KEY);
            $this->error(self::MIDDLE_NAME_KEY);
            $middleNameValid = false;
        }
        if (empty($nameData['lastName'])) {
            $this->setMessage(self::MSG_LAST_NAME_IS_EMPTY, self::LAST_NAME_KEY);
            $this->error(self::LAST_NAME_KEY);
            $lastNameValid = false;
        }
        if (strlen($nameData['lastName']) > self::FIELD_MAX_LENGTH) {
            $this->setMessage(self::MSG_LAST_NAME_IS_TOO_LONG, self::LAST_NAME_KEY);
            $this->error(self::LAST_NAME_KEY);
            $lastNameValid = false;
        }

        return $firstNameValid && $middleNameValid && $lastNameValid;
    }
}
