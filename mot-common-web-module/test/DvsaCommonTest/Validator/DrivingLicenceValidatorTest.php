<?php

namespace DvsaCommonTest\Validator;

use DoctrineORMModule\Proxy\__CG__\DvsaEntities\Entity\LicenceCountry;
use DvsaCommon\Enum\LicenceCountryCode;
use DvsaCommon\Validator\DrivingLicenceValidator;
use PHPUnit_Framework_TestCase;

/**
 * Test for {@link \DvsaCommon\Validator\DrivingLicenceValidator}.
 */
class DrivingLicenceValidatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var DrivingLicenceValidator
     */
    private $validator;

    /**
     * Test set up
     * @return null
     */
    public function setUp()
    {
        $this->validator = new DrivingLicenceValidator;
    }

    /**
     * @dataProvider validationInputProvider
     * @param array $licenceData
     * @param array $errorMessages
     */
    public function testValidation(array $licenceData, array $errorMessages = [])
    {
        $this->assertSame(empty($errorMessages), $this->validator->isValid($licenceData));
        $this->assertSame($errorMessages, array_values($this->validator->getMessages()));
    }

    /**
     * @return array
     */
    public function validationInputProvider()
    {
        $greatBritainRegion = LicenceCountryCode::GREAT_BRITAIN_ENGLAND_SCOTLAND_AND_WALES;
        $northernIreland = LicenceCountryCode::NORTHERN_IRELAND;
        $other = LicenceCountryCode::NON_UNITED_KINGDOM;

        return [
            // Valid licence number and region combinations

            // Valid: male GB driving licence
            [['drivingLicenceNumber' => 'TADIA807217BM9PC', 'drivingLicenceRegion' => $greatBritainRegion]],

            // Valid: female GB driving licence
            [['drivingLicenceNumber' => 'TADIA857217BM9PC', 'drivingLicenceRegion' => $greatBritainRegion]],

            // Valid: male with 1 first name initial
            [['drivingLicenceNumber' => 'TADIA807217B99PC', 'drivingLicenceRegion' => $greatBritainRegion]],

            // Valid: female with 1 first name initial
            [['drivingLicenceNumber' => 'TADIA857217B99PC', 'drivingLicenceRegion' => $greatBritainRegion]],

            // Valid: male with surname of 4 characters
            [['drivingLicenceNumber' => 'TADI9807217B99PC', 'drivingLicenceRegion' => $greatBritainRegion]],

            // Valid: female with surname of 4 characters
            [['drivingLicenceNumber' => 'TADI9857217B99PC', 'drivingLicenceRegion' => $greatBritainRegion]],

            // Valid: NI driving licence format
            [['drivingLicenceNumber' => '12345678', 'drivingLicenceRegion' => $northernIreland]],

            // Valid: 'other' region licence
            [['drivingLicenceNumber' => '123-456-789', 'drivingLicenceRegion' => $other]],

            // Invalid licence checks

            [
                // Invalid: GB driving licence has additional character
                ['drivingLicenceNumber' => 'TADIA807217BM9PCX', 'drivingLicenceRegion' => $greatBritainRegion],
                [DrivingLicenceValidator::MSG_INVALID_GB_LICENCE_FORMAT],
            ],
            [
                // Invalid: GB driving licence has invalid male day and month of birth (30/02)
                ['drivingLicenceNumber' => 'TADIA802307BM9PC', 'drivingLicenceRegion' => $greatBritainRegion],
                [DrivingLicenceValidator::MSG_INVALID_GB_LICENCE_FORMAT],
            ],
            [
                // Invalid: GB driving licence has invalid female day and month of birth (30/02)
                ['drivingLicenceNumber' => 'TADIA852307BM9PC', 'drivingLicenceRegion' => $greatBritainRegion],
                [DrivingLicenceValidator::MSG_INVALID_GB_LICENCE_FORMAT],
            ],
            [
                // Invalid: GB driving licence has invalid male day and month of birth (01/13)
                ['drivingLicenceNumber' => 'TADIA813017BM9PC', 'drivingLicenceRegion' => $greatBritainRegion],
                [DrivingLicenceValidator::MSG_INVALID_GB_LICENCE_FORMAT],
            ],
            [
                // Invalid: GB driving licence has invalid female day and month of birth (01/13)
                ['drivingLicenceNumber' => 'TADIA863017BM9PC', 'drivingLicenceRegion' => $greatBritainRegion],
                [DrivingLicenceValidator::MSG_INVALID_GB_LICENCE_FORMAT],
            ],
            [
                // Invalid: NI driving licence has additional character
                ['drivingLicenceNumber' => '123456789', 'drivingLicenceRegion' => $northernIreland],
                [DrivingLicenceValidator::MSG_INVALID_NI_LICENCE_FORMAT],
            ],
            [
                // Invalid: NI driving licence contains letter
                ['drivingLicenceNumber' => '1234567A', 'drivingLicenceRegion' => $northernIreland],
                [DrivingLicenceValidator::MSG_INVALID_NI_LICENCE_FORMAT],
            ],
            [
                // Invalid: Rest of World licence exceeds maximum length of 25 characters
                ['drivingLicenceNumber' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'drivingLicenceRegion' => $other],
                [DrivingLicenceValidator::MSG_REST_OF_WORLD_MAX_LENGTH],
            ],

            // Empty licence numbers

            [
                ['drivingLicenceNumber' => '', 'drivingLicenceRegion' => $greatBritainRegion],
                [DrivingLicenceValidator::MSG_MUST_NOT_BE_EMPTY],
            ],
            [
                ['drivingLicenceNumber' => '', 'drivingLicenceRegion' => $northernIreland],
                [DrivingLicenceValidator::MSG_MUST_NOT_BE_EMPTY],
            ],
            [
                ['drivingLicenceNumber' => '', 'drivingLicenceRegion' => $other],
                [DrivingLicenceValidator::MSG_MUST_NOT_BE_EMPTY],
            ],
        ];
    }
}
