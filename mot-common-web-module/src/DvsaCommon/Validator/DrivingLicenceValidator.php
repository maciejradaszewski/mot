<?php

namespace DvsaCommon\Validator;

use DvsaCommon\Enum\LicenceCountryCode;
use Zend\Validator\AbstractValidator;

/**
 * Class DrivingLicenceValidator
 * @package DvsaCommon\Validator
 */
class DrivingLicenceValidator extends AbstractValidator
{
    const GB_LICENCE_REGION = LicenceCountryCode::GREAT_BRITAIN_ENGLAND_SCOTLAND_AND_WALES;
    const NI_LICENCE_REGION = LicenceCountryCode::NORTHERN_IRELAND;
    const OTHER_LICENCE_REGION = LicenceCountryCode::NON_UNITED_KINGDOM;
    const LICENCE_NUMBER_KEY = 'drivingLicenceNumber';
    const LICENCE_REGION_KEY = 'drivingLicenceRegion';

    const MSG_INVALID_GB_LICENCE_FORMAT = 'must be a valid Great Britain driving licence';
    const MSG_INVALID_NI_LICENCE_FORMAT = 'must be a valid Northern Ireland driving licence';
    const MSG_MUST_NOT_BE_EMPTY = 'you must enter a driving licence number';
    const MSG_REST_OF_WORLD_MAX_LENGTH = 'must be 25 characters or less';
    const MSG_LICENCE_REGION_EMPTY = 'you must choose an issuing country';

    /**
     * @var array
     */
    protected $messageTemplates = [
        self::LICENCE_NUMBER_KEY => true,
        self::LICENCE_REGION_KEY => true,
    ];

    /**
     * @var array
     */
    protected $licenceFieldLabels = [
        self::LICENCE_NUMBER_KEY => 'Driving licence',
        self::LICENCE_REGION_KEY => 'Issuing country',
    ];

    /**
     * @param array $licenceData
     * @return bool
     */
    public function isValid($licenceData)
    {
        return $this->validate($licenceData);
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
    }

    /**
     * @param array $licenceData Array containing drivingLicenceNumber and drivingLicenceRegion
     * @access private
     * @return bool
     */
    private function validate(array $licenceData)
    {
        $licenceNumberValid = true;
        $licenceRegionValid = true;

        if (empty($licenceData['drivingLicenceNumber'])) {
            $this->setMessage(self::MSG_MUST_NOT_BE_EMPTY, self::LICENCE_NUMBER_KEY);
            $this->error(self::LICENCE_NUMBER_KEY);
            $licenceNumberValid = false;
        } else {
            $isGbLicenceRegion = $licenceData['drivingLicenceRegion'] == self::GB_LICENCE_REGION;
            $isNiLicenceRegion = $licenceData['drivingLicenceRegion'] == self::NI_LICENCE_REGION;
            $isRestOfWorldRegion = $licenceData['drivingLicenceRegion'] == self::OTHER_LICENCE_REGION;

            if ($isGbLicenceRegion && !preg_match($this->getGbLicencePattern(), $licenceData['drivingLicenceNumber'])) {
                $this->setMessage(self::MSG_INVALID_GB_LICENCE_FORMAT, self::LICENCE_NUMBER_KEY);
                $this->error(self::LICENCE_NUMBER_KEY);
                $licenceNumberValid = false;
            } elseif ($isNiLicenceRegion && !preg_match('/^[0-9]{8}$/', $licenceData['drivingLicenceNumber'])) {
                $this->setMessage(self::MSG_INVALID_NI_LICENCE_FORMAT, self::LICENCE_NUMBER_KEY);
                $this->error(self::LICENCE_NUMBER_KEY);
                $licenceNumberValid = false;
            } elseif ($isRestOfWorldRegion && strlen($licenceData['drivingLicenceNumber']) > 25) {
                $this->setMessage(self::MSG_REST_OF_WORLD_MAX_LENGTH, self::LICENCE_NUMBER_KEY);
                $this->error(self::LICENCE_NUMBER_KEY);
                $licenceNumberValid = false;
            }
        }

        if (empty($licenceData['drivingLicenceRegion'])) {
            $this->setMessage(self::MSG_LICENCE_REGION_EMPTY, self::LICENCE_REGION_KEY);
            $this->error(self::LICENCE_REGION_KEY);
            $licenceRegionValid = false;
        }

        return $licenceNumberValid && $licenceRegionValid;
    }

    /**
     * Returns the regular expression to match GB licences
     * @access private
     * @return string
     */
    private function getGbLicencePattern()
    {
        $gbLicencePattern  = '/^([A-Z]{5}|[A-Z]{4}9|[A-Z]{3}9{2}|[A-Z]{2}9{3}|[A-Z]9{4})'; // First 5 letters of surname, padded with 9 if fewer than 5 characters
        $gbLicencePattern .= '[0-9]'; // The decade digit from the year of birth
        $gbLicencePattern .= '([05][1-9]|[16][0-2])'; // The month of birth (first digit incremented by 5 if female)
        $gbLicencePattern .= '(0[1-9]|[12][0-9]|(?<![05]2)30|(?<!([05][2469]|[16]1))31)'; // The date within the month of birth
        $gbLicencePattern .= '[0-9]'; // The year digit from the year of birth
        $gbLicencePattern .= '[A-Z][A-Z9]'; // First 2 initials of first names, padded with 9 if no middle name
        $gbLicencePattern .= '[0-9]'; // Arbitrary digit (usually 9, but decremented if the first 13 characters match)
        $gbLicencePattern .= '[A-Z]{2}$/'; // 2 computer check digits
        $gbLicencePattern .= 'i'; // case insensitive matching

        return $gbLicencePattern;
    }
}
