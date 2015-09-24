<?php

namespace SiteApiTest\Service\Validator;

use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\Enum\SiteStatusCode;
use DvsaCommon\Enum\SiteTypeCode;
use SiteApi\Service\Validator\SiteDetailsValidator;

class SiteDetailsValidatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var VehicleTestingStationDto
     */
    protected $vtsDto;

    /**
     * @var SiteDetailsValidator
     */
    protected $validator;

    protected function setUp()
    {
        parent::setUp();
        $this->vtsDto = new VehicleTestingStationDto();
        $this->validator = new SiteDetailsValidator();
    }

    /**
     * @dataProvider dataProviderValidData
     */
    public function testValidatorPassesWithCorrectData(
        $siteTypeCode,
        $siteStatusCode,
        $siteName,
        $testClasses
    )
    {
        $this->createVtsDto($siteTypeCode, $siteStatusCode, $siteName, $testClasses);

        $this->validator->validate($this->vtsDto);
    }

    /**
     * @dataProvider dataProviderInvalidData
     * @expectedException \DvsaCommonApi\Service\Exception\BadRequestException
     * @expectedExceptionMessage Validation errors encountered
     */
    public function testValidatorFailsWithIncorrectData(
        $siteTypeCode,
        $siteStatusCode,
        $siteName,
        $testClasses
    )
    {
        $this->createVtsDto($siteTypeCode, $siteStatusCode, $siteName, $testClasses);

        $this->validator->validate($this->vtsDto);
    }

    public function dataProviderValidData()
    {
        return [
            [
              "siteTypeCode" => SiteTypeCode::VEHICLE_TESTING_STATION,
              "siteStatusCode" => SiteStatusCode::APPROVED,
              "siteName" => "test name",
              "testClasses" => ["1", "2"],
            ],
            [
              "siteTypeCode" => SiteTypeCode::AREA_OFFICE,
              "siteStatusCode" => SiteStatusCode::LAPSED,
              "siteName" => "test name",
              "testClasses" => ["1", "2", "4"],
            ],
            [
              "siteTypeCode" => SiteTypeCode::VEHICLE_RECORDS_OFFICE,
              "siteStatusCode" => SiteStatusCode::EXTINCT,
              "siteName" => "test name",
              "testClasses" => ["1", "2", "4", "7"],
            ],
        ];
    }

    public function dataProviderInvalidData()
    {
        return [
            // no type
            [
                "siteTypeCode" => null,
                "siteStatusCode" => SiteStatusCode::APPROVED,
                "siteName" => "test name",
                "testClasses" => ["1", "2"],
            ],
            // invalid site status code
            [
                "siteTypeCode" => SiteTypeCode::VEHICLE_TESTING_STATION,
                "siteStatusCode" => "invalidCode",
                "siteName" => "test name",
                "testClasses" => ["1", "2"],
            ],
            // no status
            [
                "siteTypeCode" => SiteTypeCode::VEHICLE_RECORDS_OFFICE,
                "siteStatusCode" => null,
                "siteName" => "test name",
                "testClasses" => ["1", "2", "4", "7"],
            ],
            // no test classes
            [
                "siteTypeCode" => SiteTypeCode::VEHICLE_TESTING_STATION,
                "siteStatusCode" => SiteStatusCode::APPROVED,
                "siteName" => "test name",
                "testClasses" => [],
            ],
            // no test classes
            [
                "siteTypeCode" => SiteTypeCode::VEHICLE_TESTING_STATION,
                "siteStatusCode" => SiteStatusCode::APPROVED,
                "siteName" => "test name",
                "testClasses" => null,
            ],
        ];
    }

    /**
     * @param $siteTypeCode
     * @param $siteStatusCode
     * @param $siteName
     * @param $testClasses
     */
    private function createVtsDto($siteTypeCode, $siteStatusCode, $siteName, $testClasses)
    {
        $this->vtsDto
            ->setType($siteTypeCode)
            ->setStatus($siteStatusCode)
            ->setName($siteName)
            ->setTestClasses($testClasses);
    }
}