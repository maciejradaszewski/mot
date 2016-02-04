<?php

namespace DvsaCommon\Model;

use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\Utility\ArrayUtils;

class AuthorisedExaminerPatchModel
{
    const REGISTERED_ADDRESS_LINE_1 = 'registeredAddressLine1';
    const REGISTERED_ADDRESS_LINE_2 = 'registeredAddressLine2';
    const REGISTERED_ADDRESS_LINE_3 = 'registeredAddressLine3';
    const REGISTERED_ADDRESS_TOWN = 'registeredAddressTown';
    const REGISTERED_ADDRESS_COUNTRY = 'registeredAddressCountry';
    const REGISTERED_ADDRESS_POSTCODE = 'registeredAddressPostcode';

    const REGISTERED_EMAIL = 'registeredEmail';
    const REGISTERED_PHONE = 'registeredPhone';

    const CORRESPONDENCE_ADDRESS_LINE_1 = 'correspondenceAddressLine1';
    const CORRESPONDENCE_ADDRESS_LINE_2 = 'correspondenceAddressLine2';
    const CORRESPONDENCE_ADDRESS_LINE_3 = 'correspondenceAddressLine3';
    const CORRESPONDENCE_ADDRESS_TOWN = 'correspondenceAddressTown';
    const CORRESPONDENCE_ADDRESS_COUNTRY = 'correspondenceAddressCountry';
    const CORRESPONDENCE_ADDRESS_POSTCODE = 'correspondenceAddressPostcode';

    const CORRESPONDENCE_EMAIL = 'correspondenceEmail';
    const CORRESPONDENCE_PHONE = 'correspondencePhone';

    const NAME = 'name';
    const TRADING_NAME = 'tradingName';
    const TYPE = 'type';
    const STATUS = 'status';
    const COMPANY_NUMBER = 'companyNumber';
    const AREA_OFFICE = 'areaOffice';

    private $addressLine1Field;
    private $addressLine2Field;
    private $addressLine3Field;
    private $townField;
    private $postcodeField;
    private $countryField;
    private $phoneField;
    private $emailField;
    private $organisationContactTypeCode;

    private function __construct($addressLine1, $addressLine2, $addressLine3, $town, $postcode, $country, $phone, $email, $organisationContactType)
    {
        $this->addressLine1Field = $addressLine1;
        $this->addressLine2Field = $addressLine2;
        $this->addressLine3Field = $addressLine3;
        $this->townField = $town;
        $this->postcodeField = $postcode;
        $this->countryField = $country;
        $this->phoneField = $phone;
        $this->emailField = $email;
        $this->organisationContactTypeCode = $organisationContactType;
    }

    public function getAddressLine1Field()
    {
        return $this->addressLine1Field;
    }

    public function getAddressLine2Field()
    {
        return $this->addressLine2Field;
    }

    public function getAddressLine3Field()
    {
        return $this->addressLine3Field;
    }

    public function getTownField()
    {
        return $this->townField;
    }

    public function getPostcodeField()
    {
        return $this->postcodeField;
    }

    public function getCountryField()
    {
        return $this->countryField;
    }

    public function getPhoneField()
    {
        return $this->phoneField;
    }

    public function getEmailField()
    {
        return $this->emailField;
    }

    public function getOrganisationContactTypeCode()
    {
        return $this->organisationContactTypeCode;
    }

    public static function createForRegisteredContact()
    {
        return new AuthorisedExaminerPatchModel (
            self::REGISTERED_ADDRESS_LINE_1,
            self::REGISTERED_ADDRESS_LINE_2,
            self::REGISTERED_ADDRESS_LINE_3,
            self::REGISTERED_ADDRESS_TOWN,
            self::REGISTERED_ADDRESS_POSTCODE,
            self::REGISTERED_ADDRESS_COUNTRY,
            self::REGISTERED_PHONE,
            self::REGISTERED_EMAIL,
            OrganisationContactTypeCode::REGISTERED_COMPANY
        );
    }

    public static function createForCorrespondenceContact()
    {
        return new AuthorisedExaminerPatchModel (
            self::CORRESPONDENCE_ADDRESS_LINE_1,
            self::CORRESPONDENCE_ADDRESS_LINE_2,
            self::CORRESPONDENCE_ADDRESS_LINE_3,
            self::CORRESPONDENCE_ADDRESS_TOWN,
            self::CORRESPONDENCE_ADDRESS_POSTCODE,
            self::CORRESPONDENCE_ADDRESS_COUNTRY,
            self::CORRESPONDENCE_PHONE,
            self::CORRESPONDENCE_EMAIL,
            OrganisationContactTypeCode::CORRESPONDENCE
        );
    }

    public static function getRegisteredAddressPatchProperties()
    {
        return [
            self::REGISTERED_ADDRESS_LINE_1,
            self::REGISTERED_ADDRESS_LINE_2,
            self::REGISTERED_ADDRESS_LINE_3,
            self::REGISTERED_ADDRESS_TOWN,
            self::REGISTERED_ADDRESS_COUNTRY,
            self::REGISTERED_ADDRESS_POSTCODE,
        ];
    }

    public static function isRegisteredAddressProperty($property)
    {
        return in_array($property, self::getRegisteredAddressPatchProperties());
    }

    public static function containsRegisteredAddressProperty(array $properties)
    {
        return ArrayUtils::anyMatch($properties, function($property) {
            return self::isRegisteredAddressProperty($property);
        });
    }

    public static function getCorrespondenceAddressPatchProperties()
    {
        return [
            self::CORRESPONDENCE_ADDRESS_LINE_1,
            self::CORRESPONDENCE_ADDRESS_LINE_2,
            self::CORRESPONDENCE_ADDRESS_LINE_3,
            self::CORRESPONDENCE_ADDRESS_TOWN,
            self::CORRESPONDENCE_ADDRESS_COUNTRY,
            self::CORRESPONDENCE_ADDRESS_POSTCODE,
        ];
    }

    public static function isCorrespondenceAddressProperty($property)
    {
        return in_array($property, self::getCorrespondenceAddressPatchProperties());
    }

    public static function containsCorrespondenceAddressProperty(array $properties)
    {
        return ArrayUtils::anyMatch($properties, function($property) {
            return self::isCorrespondenceAddressProperty($property);
        });
    }
}
