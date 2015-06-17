<?php

namespace DvsaMotApiTest\Factory;

use DvsaCommon\Constants\OdometerReadingResultType;
use DvsaCommon\Constants\OdometerUnit;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Dto\Common\OdometerReadingDTO;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommonTest\TestUtils\TestCase;
use DvsaEntities\Entity\Address;
use DvsaEntities\Entity\AuthorisationForTestingMot;
use DvsaEntities\Entity\AuthorisationForTestingMotStatus;
use DvsaEntities\Entity\ContactDetail;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\MotTestReasonForRejection;
use DvsaEntities\Entity\MotTestStatus;
use DvsaEntities\Entity\MotTestType;
use DvsaEntities\Entity\OdometerReading;
use DvsaEntities\Entity\Organisation;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\Site;
use DvsaEntities\Entity\SiteContactType;
use DvsaEntities\Entity\VehicleClass;

/**
 * Class MotTestObjectsFactory
 */
class MotTestObjectsFactory
{
    /**
     * @return MotTest
     */
    public static function activeMotTest()
    {
        /** @var MotTest $motTest */
        $motTest = self::motTest(MotTestStatusName::ACTIVE);
        $motTest->setIssuedDate(null)->setCompletedDate(null)->setExpiryDate(null);
        return $motTest;
    }

    /**
     * @return MotTest
     */
    public static function activeDemoTest()
    {
        /** @var MotTest $motTest */
        $motTest = self::motTest(MotTestStatusName::ACTIVE);
        $motTest->setIssuedDate(null)->setCompletedDate(null)->setExpiryDate(null)
            ->setMotTestType((new MotTestType())->setCode(MotTestTypeCode::ROUTINE_DEMONSTRATION_TEST));
        return $motTest;
    }

    public static function addPersonAuthorisationForClass(
        Person $person,
        $vehicleClassCode,
        $status
    ) {
        $auth = new AuthorisationForTestingMot();
        $authStatus = new AuthorisationForTestingMotStatus();
        $authStatus->setCode($status);
        $auth->setStatus($authStatus);
        $auth->setVehicleClass(new VehicleClass($vehicleClassCode));
        $person->addAuthorisationForTestingMot($auth);
    }

    public static function addTestAuthorisationForClass(
        MotTest $motTest,
        $vehicleClassCode,
        $status
    ) {
        $person = $motTest->getTester();
        self::addPersonAuthorisationForClass($person, $vehicleClassCode, $status);
        return $motTest;
    }

    /**
     * @param string $statusCode
     *
     * @return MotTest
     */
    public static function motTest($statusCode = MotTestStatusName::PASSED)
    {
        return self::createTest(MotTestTypeCode::NORMAL_TEST, $statusCode);
    }

    /**
     * @param string $testTypeCode value from \DvsaCommon\Enum\MotTestTypeCode
     * @param string $status
     *
     * @return MotTest
     */
    public static function createTest($testTypeCode, $status = MotTestStatusName::PASSED)
    {
        $testCase = new TestCase();
        $motTestStatus = $testCase->getMockBuilder(MotTestStatus::class)->getMock();
        $motTestStatus
            ->expects($testCase->any())
            ->method("getName")
            ->willReturn($status);

        $motTest = new MotTest();
        $motTestType = (new MotTestType())->setCode($testTypeCode);
        $motTest
            ->setMotTestType($motTestType)
            ->setStatus($motTestStatus);
        self::setTestData($motTest);

        return $motTest;
    }

    public static function addRfr(MotTest $motTest, $rfrType)
    {
        $reasonForRejectionType = (new MotTestReasonForRejection())->setType($rfrType);
        $motTest->addMotTestReasonForRejection($reasonForRejectionType);
    }

    public static function tester($id = 1, $username = "username")
    {
        return (new Person())->setId($id)
            ->setUsername($username)
            ->addVehicleTestingStation(self::vts(1));
    }

    public static function vts($id, $siteNumber = null)
    {
        $site = new Site();

        $contactDetail = (new ContactDetail())
            ->setAddress(
                (new Address())
                    ->setAddressLine1("exampleLine1")
                    ->setAddressLine2("exampleLine2")
                    ->setAddressLine3("exampleLine3")
                    ->setAddressLine4("exampleLine4")
                    ->setTown("exampleTown")
                    ->setCountry("exampleCountry")
                    ->setPostcode("BS41GG")
            );
        $contactType = (new SiteContactType())
            ->setCode(SiteContactTypeCode::BUSINESS)
            ->setId(2);

        $org = new Organisation();
        return $site
            ->setId($id)
            ->setName("exampleName")
            ->setSiteNumber($siteNumber)
            ->setContact($contactDetail, $contactType)
            ->setOrganisation($org);
    }

    public static function odometerReading(
        $value = 123,
        $unit = OdometerUnit::MILES,
        $resultType = OdometerReadingResultType::OK
    ) {
        return OdometerReading::create()->setValue($value)
            ->setUnit($unit)->setResultType($resultType);
    }

    /**
     * @param int    $value
     * @param string $unit
     * @param string $resultType
     *
     * @return OdometerReadingDTO
     */
    public static function odometerReadingDTO(
        $value = 123,
        $unit = OdometerUnit::MILES,
        $resultType = OdometerReadingResultType::OK
    ) {
        return OdometerReadingDTO::create()->setValue($value)
            ->setUnit($unit)->setResultType($resultType);
    }

    private static function setTestData(MotTest $motTest)
    {
        $motTest
            ->setStartedDate(DateUtils::toDate("2014-05-01"))
            ->setExpiryDate(DateUtils::toDate("2015-05-01"))
            ->setIssuedDate(DateUtils::toDate("2014-05-01"))
            ->setCompletedDate(DateUtils::toDate("2014-05-01"))
            ->setOdometerReading(self::odometerReading())
            ->setHasRegistration(true)
            ->setPrimaryColour(VehicleObjectsFactory::colour(22))
            ->setSecondaryColour(VehicleObjectsFactory::colour(23))
            ->setVehicleTestingStation(self::vts(1))
            ->setNumber("ABC1234XYZ")
            ->setId(12345)
            ->setTester(self::tester())
            ->setVehicle(VehicleObjectsFactory::vehicle())
            ->setVehicleClass($motTest->getVehicle()->getVehicleClass());
    }
}
