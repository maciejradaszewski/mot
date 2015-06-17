<?php

namespace DvsaMotApiTest\Service\Mapper;

use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Dto\Common\ColourDto;
use DvsaCommon\Dto\Common\MotTestDto;
use DvsaCommon\Dto\Common\MotTestTypeDto;
use DvsaCommon\Dto\Person\PersonDto;
use DvsaCommon\Dto\Vehicle\CountryDto;
use DvsaCommon\Dto\Vehicle\VehicleDto;
use DvsaCommon\Dto\Vehicle\VehicleParamDto;
use DvsaCommon\Dto\VehicleClassification\VehicleClassDto;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommon\Enum\SiteBusinessRoleCode;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\Date\TestDateTimeHolder;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\Address;
use DvsaEntities\Entity\AuthorisationForAuthorisedExaminer;
use DvsaEntities\Entity\BodyType;
use DvsaEntities\Entity\BrakeTestResultClass12;
use DvsaEntities\Entity\BrakeTestResultClass3AndAbove;
use DvsaEntities\Entity\Colour;
use DvsaEntities\Entity\ContactDetail;
use DvsaEntities\Entity\CountryOfRegistration;
use DvsaEntities\Entity\FuelType;
use DvsaEntities\Entity\Language;
use DvsaEntities\Entity\Make;
use DvsaEntities\Entity\Model;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\MotTestReasonForRejection;
use DvsaEntities\Entity\MotTestStatus;
use DvsaEntities\Entity\MotTestType;
use DvsaEntities\Entity\Organisation;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\ReasonForRejection;
use DvsaEntities\Entity\ReasonForRejectionDescription;
use DvsaEntities\Entity\Site;
use DvsaEntities\Entity\SiteContactType;
use DvsaEntities\Entity\SiteType;
use DvsaEntities\Entity\TestItemCategoryDescription;
use DvsaEntities\Entity\TestItemSelector;
use DvsaEntities\Entity\Vehicle;
use DvsaEntities\Entity\VehicleClass;
use DvsaMotApi\Service\Mapper\MotTestMapper;
use DvsaMotApi\Service\MotTestDateHelper;
use DvsaMotApiTest\Service\MotTestServiceTest;
use PHPUnit_Framework_Assert;
use VehicleApi\Service\VehicleSearchService;

/**
 * Class MotTestMapperTest
 */
class MotTestMapperTest extends AbstractServiceTestCase
{

    const MOCK_BRAKE_TEST_RESULT_SERVICE = 'mockBrakeTestResultService';
    const MOCK_VEHICLE_SERVICE = 'mockVehicleService';
    const MOCK_HYDRATOR = 'mockHydrator';
    const MOCK_CERTIFICATE_EXPIRY_SERVICE = 'mockCertificateExpiryService';
    const MOCK_STATUS_SERVICE = 'mockStatusService';
    const MOCK_DATE_SERVICE = 'mockDateService';
    const MOCK_PARAMOBFUSCATOR = 'paramObfuscator';

    public function testMotTestMappedCorrectlyToDto()
    {
        //given
        $motTestNumber = 1;

        //// Setup objects for MotTest object to hold
        $vehicleClass = '4';

        $tester = $this->getTestTester();
        $testType = (new MotTestType())->setCode(MotTestTypeCode::NORMAL_TEST);

        $make = new Make();
        $model = new Model();
        $model->setMake($make);

        $vehicle = new Vehicle();
        $vehicle
            ->setVehicleClass(new VehicleClass($vehicleClass))
            ->setColour(new Colour())
            ->setFuelType(new FuelType())
            ->setBodyType(new BodyType())
            ->setNoOfSeatBelts(99)
            ->setNewAtFirstReg(true)
            ->setCountryOfRegistration((new CountryOfRegistration()))
            ->setModel($model);

        $address = new Address();
        $address->setAddressLine1('Johns Garage');

        $site = new Site();

        $contactDetail = (new ContactDetail())->setAddress($address);
        $contactType = (new SiteContactType())->setCode(SiteContactTypeCode::BUSINESS);
        $site->setContact($contactDetail, $contactType);

        $site->setType(new SiteType());

        $this->addOrg($site);

        $brakeTestResult = new BrakeTestResultClass3AndAbove();
        $brakeTestResultClass12 = new BrakeTestResultClass12();
        $motRfrAdvisory = self::getTestMotTestReasonForRejection('ADVISORY');
        $testDate = DateUtils::toDate('2013-09-30');
        $dateHolder = new TestDateTimeHolder($testDate);

        // Setup MotTest object
        $motTest = new MotTest();
        /** @var \DateTime $startedDate */
        $startedDate = $dateHolder->getCurrent();
        $motTest->setStartedDate(clone $startedDate);
        $expiryDate = clone $startedDate;
        $expiryDate->add(\DateInterval::createFromDateString('+1 year -1 day'));

        $motTest
            ->setStatus($this->createMotTestActiveStatus())
            ->setNumber($motTestNumber)
            ->setTester($tester)
            ->setMotTestType($testType)
            ->setVehicle($vehicle)
            ->setVehicleClass((new VehicleClass())->setCode($vehicleClass))
            ->setMake((new Make())->setName('MAKE'))
            ->setModel((new Model())->setName('MODEL'))
            ->setCountryOfRegistration((new CountryOfRegistration())->setName('COR'))
            ->setVehicleTestingStation($site)
            ->setBrakeTestResultClass3AndAbove($brakeTestResult)
            ->setBrakeTestResultClass12($brakeTestResultClass12)
            ->setPrsMotTest((new MotTest())->setNumber(2))
            ->addMotTestReasonForRejection($motRfrAdvisory);

        $vtsData = ['id' => 3, 'address' => 'Johns Garage', 'authorisedExaminer' => 42, 'comments' => [], 'primaryTelephone' => null];
        $brakeTestData = ['id' => 3, 'generalPass' => 'true'];

        // ---- Setup expected data array   ----
        //  --  vehicle --
        $vehicleDto = (new VehicleDto())
            ->setVehicleClass(
                (new VehicleClassDto())
                    ->setCode($vehicle->getVehicleClass()->getCode())
            )
            ->setColour(
                (new ColourDto())
                    ->setName($vehicle->getColour()->getName())
            )
            ->setFuelType(
                (new VehicleParamDto())
                    ->setName($vehicle->getFuelType()->getName())
            )
            ->setBodyType(new VehicleParamDto())
            ->setTransmissionType(new VehicleParamDto())
            ->setNoOfSeatBelts($vehicle->getNoOfSeatBelts())
            ->setIsNewAtFirstReg($vehicle->getNewAtFirstReg());

        //  --  other   --
        $expectedData = (new MotTestDto())
            ->setStatus(MotTestStatusName::ACTIVE)
            ->setMotTestNumber($motTestNumber)
            ->setStartedDate(DateTimeApiFormat::dateTime($startedDate))
            ->setTester(
                (new PersonDto())
                    ->setId(1)
                    ->setUsername('tester1')
            )
            ->setVehicle($vehicleDto)
            ->setVehicleTestingStation($vtsData)
            ->setTestType((new MotTestTypeDto())->setCode('NT'));

        // TODO add mock for new entities
        $expectedRfr1 = [
            'rfrId'                       => 1,
            'name'                        => 'Rear Stop lamp',
            'failureText'                 => 'adversely affected by the operation of another lamp',
            'inspectionManualReference'   => '1.2.1f',
            'testItemSelectorId'          => 12,
            'testItemSelectorDescription' => 'aaa'
        ];

        $expectedData->setReasonsForRejection(
            [
                'ADVISORY' => [$expectedRfr1]
            ]
        );

        // setup motTestMapper mock
        $mocks = $this->getMocksForMotTestMapperService();

        // Setup additional expectedData that relied on motTestMapper function
        $expectedData
            ->setBrakeTestResult($brakeTestData)
            ->setPendingDetails(
                [
                    'currentSubmissionStatus' => 'INCOMPLETE',
                    'issuedDate'              => null,
                    'expiryDate'              => null,
                ]
            )
            ->setVehicleClass((new VehicleClassDto())->setCode('4'))
            ->setBrakeTestCount(2)
            ->setMake('MAKE')
            ->setModel('MODEL')
            ->setTesterBrakePerformanceNotTested(false)
            ->setCountryOfRegistration((new CountryDto())->setName('COR'))
            ->setPrsMotTestNumber(2);

        $hydratorCalls = [
            [self::WITH => $site, self::WILL => $vtsData],
            [self::WITH => $motRfrAdvisory, self::WILL => $expectedRfr1],
        ];
        $this->setupHandlerForHydratorMultipleCalls($mocks['mockHydrator'], $hydratorCalls);

        $mocks['mockBrakeTestResultService']->expects($this->once())
            ->method('extract')
            ->with($brakeTestResult)
            ->will($this->returnValue($brakeTestData));

        $mocks[self::MOCK_STATUS_SERVICE]->expects($this->once())
            ->method('getMotTestPendingStatus')
            ->with($motTest)
            ->will($this->returnValue('INCOMPLETE'));

        $mocks[self::MOCK_STATUS_SERVICE]->expects($this->once())
            ->method('hasBrakePerformanceNotTestedRfr')
            ->with($motTest)
            ->will($this->returnValue(false));

        //when
        $motTestMapper = $this->constructMotTestMapperWithMocks($mocks);
        $this->mockClassField($motTestMapper, 'dateTimeHolder', $dateHolder);

        $resultMotTestData = $motTestMapper->mapMotTest($motTest);
        $this->assertEquals($expectedData, $resultMotTestData);
    }

    public function testMotTestOriginalPopulated()
    {
        //given
        $motTest = MotTestServiceTest::getTestMotTestEntity();
        $motTest->setStatus($this->createMotTestActiveStatus());
        $motTest->setMotTestIdOriginal(clone $motTest);
        $mocks = $this->getMocksForMotTestMapperService();

        //when
        $motTestMapper = $this->constructMotTestMapperWithMocks($mocks);
        /** @var MotTestDto $resultMotTestData */
        $resultMotTestData = $motTestMapper->mapMotTest($motTest);

        //then
        $original = $resultMotTestData->getMotTestOriginal();
        $resultMotTestData->setMotTestOriginal(null);
        $this->assertEquals($resultMotTestData, $original);
    }

    protected static function getTestTester($roleText = SiteBusinessRoleCode::TESTER)
    {
        $tester = new Person();
        $tester->setId(1);
        $tester->setUsername('tester1');

        return $tester;
    }

    /**
     * @param Site $vehicleTestingStation
     */
    protected function addOrg($vehicleTestingStation)
    {
        $org = new Organisation();
        $org->setSlotBalance(MotTestServiceTest::SLOTS_COUNT_START);
        $org->setId(9);
        $org->setAuthorisedExaminer(
            (new AuthorisationForAuthorisedExaminer())
                ->setId(42)
        );
        $vehicleTestingStation->setOrganisation($org);
    }

    protected function getMocksForMotTestMapperService()
    {
        $mockHydrator = $this->getMockHydrator();

        $mockBrakeTestResultService = $this->getMockWithDisabledConstructor(
            \DvsaMotApi\Service\BrakeTestResultService::class
        );
        $mockVehicleSearchService = $this->getMockWithDisabledConstructor(VehicleSearchService::class);
        $mockCertificateExpiryService = $this->getMockWithDisabledConstructor(
            \DvsaMotApi\Service\CertificateExpiryService::class
        );
        $motTestStatusService = $this->getMockWithDisabledConstructor(\DvsaMotApi\Service\MotTestStatusService::class);

        $motTestDateService = $this->getMockWithDisabledConstructor(MotTestDateHelper::class);

        $mockParamObfuscator = $this->getMockWithDisabledConstructor(ParamObfuscator::class);

        return [
            self::MOCK_BRAKE_TEST_RESULT_SERVICE  => $mockBrakeTestResultService,
            self::MOCK_VEHICLE_SERVICE            => $mockVehicleSearchService,
            self::MOCK_HYDRATOR                   => $mockHydrator,
            self::MOCK_CERTIFICATE_EXPIRY_SERVICE => $mockCertificateExpiryService,
            self::MOCK_STATUS_SERVICE             => $motTestStatusService,
            self::MOCK_DATE_SERVICE               => $motTestDateService,
            self::MOCK_PARAMOBFUSCATOR            => $mockParamObfuscator
        ];
    }

    protected function constructMotTestMapperWithMocks($mocks)
    {
        return new MotTestMapper(
            $mocks[self::MOCK_HYDRATOR],
            $mocks[self::MOCK_BRAKE_TEST_RESULT_SERVICE],
            $mocks[self::MOCK_VEHICLE_SERVICE],
            $mocks[self::MOCK_CERTIFICATE_EXPIRY_SERVICE],
            $mocks[self::MOCK_STATUS_SERVICE],
            $mocks[self::MOCK_DATE_SERVICE],
            $mocks[self::MOCK_PARAMOBFUSCATOR]
        );
    }

    public static function getTestMotTestReasonForRejection($type = 'FAIL')
    {
        $motTestRfr = new MotTestReasonForRejection();
        $motTestRfr->setType($type);

        $rfr = new ReasonForRejection();
        $rfr->setRfrId(1);
        $rfr->setInspectionManualReference('1.2.1f');
        $rfrDescriptions = [
            (new ReasonForRejectionDescription())
                ->setLanguage((new Language())->setCode('EN'))
                ->setName('adversely affected by the operation of another lamp')
        ];
        $rfr->setDescriptions($rfrDescriptions);

        $rfrCategory = new TestItemSelector();
        $rfrCategory->setId(12);
        $rfrCategory->setDescriptions(
            [
                (new TestItemCategoryDescription())
                    ->setLanguage((new Language())->setCode('EN'))
                    ->setName('Rear lamp')
            ]
        );
        $rfr->setTestItemSelector($rfrCategory);

        $motTestRfr->setReasonForRejection($rfr);
        return $motTestRfr;
    }

    private function createMotTestActiveStatus()
    {
        $status = XMock::of(MotTestStatus::class);
        $status
            ->expects($this->any())
            ->method("getName")
            ->willReturn(MotTestStatusName::ACTIVE);

        return $status;
    }
}
