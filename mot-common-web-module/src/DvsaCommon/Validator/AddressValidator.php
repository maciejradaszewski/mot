<?php

namespace DvsaCommon\Validator;

use Zend\I18n\Validator\PostCode;
use Zend\Validator\AbstractValidator;

class AddressValidator extends AbstractValidator
{
    const FIRST_LINE_KEY = 'firstLine';
    const SECOND_LINE_KEY = 'secondLine';
    const THIRD_LINE_KEY = 'thirdLine';
    const TOWN_OR_CITY_KEY = 'townOrCity';
    const COUNTRY_KEY = 'country';
    const POSTCODE_KEY = 'postcode';

    const MAX_FIELD_LENGTH = 50;

    const MSG_FIRST_LINE_IS_EMPTY = 'you must enter the first line of the address';
    const MSG_TOWN_OR_CITY_IS_EMPTY = 'you must enter a town or city';
    const MSG_POST_CODE_IS_EMPTY = 'you must enter a postcode';
    const MSG_INVALID_POST_CODE = 'must be a valid postcode';
    const MSG_FIRST_LINE_TOO_LONG = 'must be 50 characters or less';
    const MSG_SECOND_LINE_TOO_LONG = 'must be 50 characters or less ';
    const MSG_THIRD_LINE_TOO_LONG = 'must be 50 characters or less  ';
    const MSG_TOWN_OR_CITY_TOO_LONG = 'must be 50 characters or less   ';
    const MSG_COUNTRY_TOO_LONG = 'must be 50 characters or less    ';

    /**
     * @var array
     */
    protected $messageTemplates = [
        self::FIRST_LINE_KEY => true,
        self::SECOND_LINE_KEY => true,
        self::THIRD_LINE_KEY => true,
        self::TOWN_OR_CITY_KEY => true,
        self::COUNTRY_KEY => true,
        self::POSTCODE_KEY => true,
    ];

    /**
     * @var array
     */
    protected $addressFieldLabels = [
        self::FIRST_LINE_KEY => 'Address',
        self::SECOND_LINE_KEY => 'Address line 2',
        self::THIRD_LINE_KEY => 'Address line 3',
        self::TOWN_OR_CITY_KEY => 'Town or city',
        self::COUNTRY_KEY => 'Country (optional)',
        self::POSTCODE_KEY => 'Postcode',
    ];

    /**
     * @param array $addressData
     * @return bool
     */
    public function isValid($addressData)
    {
        return $this->validate($addressData);
    }

    /**
     * @param $field
     * @return string
     */
    public function getFieldLabel($field)
    {
        if (isset($this->addressFieldLabels[$field])) {
            return $this->addressFieldLabels[$field];
        }

        return '';
    }

    /**
     * @param array $addressData
     * @return bool
     */
    private function validate(array $addressData)
    {
        $addressLine1Valid = true;
        $addressLine2Valid = true;
        $addressLine3Valid = true;
        $townOrCityValid = true;
        $countryValid = true;
        $postcodeValid = true;

        $postcodeValidator = new PostCode();
        $postcodeValidator->setLocale('en_GB');

        if ($addressData['firstLine'] == '') {
            $this->setMessage(self::MSG_FIRST_LINE_IS_EMPTY, self::FIRST_LINE_KEY);
            $this->error(self::FIRST_LINE_KEY);
            $addressLine1Valid = false;
        }

        if ($addressData['townOrCity'] == '') {
            $this->setMessage(self::MSG_TOWN_OR_CITY_IS_EMPTY, self::TOWN_OR_CITY_KEY);
            $this->error(self::TOWN_OR_CITY_KEY);
            $addressLine2Valid = false;
        }

        if ($addressData['postcode'] == '') {
            $this->setMessage(self::MSG_POST_CODE_IS_EMPTY, self::POSTCODE_KEY);
            $this->error(self::POSTCODE_KEY);
            $postcodeValid = false;
        }

        if (strlen($addressData['firstLine']) > self::MAX_FIELD_LENGTH) {
            $this->setMessage(self::MSG_FIRST_LINE_TOO_LONG, self::FIRST_LINE_KEY);
            $this->error(self::FIRST_LINE_KEY);
            $addressLine1Valid = false;
        }

        if (strlen($addressData['secondLine']) > self::MAX_FIELD_LENGTH) {
            $this->setMessage(self::MSG_SECOND_LINE_TOO_LONG, self::SECOND_LINE_KEY);
            $this->error(self::SECOND_LINE_KEY);
            $addressLine2Valid = false;
        }

        if (strlen($addressData['thirdLine']) > self::MAX_FIELD_LENGTH) {
            $this->setMessage(self::MSG_THIRD_LINE_TOO_LONG, self::THIRD_LINE_KEY);
            $this->error(self::THIRD_LINE_KEY);
            $addressLine3Valid = false;
        }

        if (strlen($addressData['townOrCity']) > self::MAX_FIELD_LENGTH) {
            $this->setMessage(self::MSG_TOWN_OR_CITY_TOO_LONG, self::TOWN_OR_CITY_KEY);
            $this->error(self::TOWN_OR_CITY_KEY);
            $townOrCityValid = false;
        }

        if (strlen($addressData['country']) > self::MAX_FIELD_LENGTH) {
            $this->setMessage(self::MSG_COUNTRY_TOO_LONG, self::COUNTRY_KEY);
            $this->error(self::COUNTRY_KEY);
            $countryValid = false;
        }

        if (!$postcodeValidator->isValid($addressData['postcode'])) {
            $this->setMessage(self::MSG_INVALID_POST_CODE, self::POSTCODE_KEY);
            $this->error(self::POSTCODE_KEY);
            $postcodeValid = false;
        }

        return $addressLine1Valid
            && $addressLine2Valid
            && $addressLine3Valid
            && $townOrCityValid
            && $countryValid
            && $postcodeValid;
    }
}