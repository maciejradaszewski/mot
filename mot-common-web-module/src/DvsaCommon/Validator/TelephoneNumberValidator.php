<?php

namespace DvsaCommon\Validator;

use Zend\Validator\AbstractValidator;

class TelephoneNumberValidator extends AbstractValidator
{
    const MSG_PHONE_NUMBER_TOO_LONG = 'must be 24 characters or less';
    const PHONE_NUMBER_KEY = 'personTelephone';

    /**
     * @var array
     */
    protected $messageTemplates = [
        self::PHONE_NUMBER_KEY => true,
    ];

    /**
     * @var array
     */
    protected $licenceFieldLabels = [
        self::PHONE_NUMBER_KEY => 'Phone number',
    ];

    /**
     * @param string $newPhoneNumber
     */
    public function isValid($newPhoneNumber)
    {
        return $this->validate($newPhoneNumber);
    }

    /**
     * @param $field
     * @return string
     */
    public function getFieldLabel($field)
    {
        if (isset($this->licenceFieldLabels[$field])) {
            return $this->licenceFieldLabels[$field];
        }
        return '';
    }

    /**
     * @param string $newPhoneNumber
     * @return bool
     */
    private function validate($newPhoneNumber)
    {
        $phoneNumberValid = true;

        // not validating number by format due to existing data containing brackets, addition symbols, spaces, etc.
        // validate number has not more than 24 characters
        if (mb_strlen($newPhoneNumber) > 24) {
            $this->setMessage(self::MSG_PHONE_NUMBER_TOO_LONG, self::PHONE_NUMBER_KEY);
            $this->error(self::PHONE_NUMBER_KEY);
            $phoneNumberValid = false;
        }

        return $phoneNumberValid;
    }

}
