<?php

namespace DvsaMotApiTest\Service;

use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\Date\TestDateTimeHolder;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\Vehicle;
use DvsaEntities\Repository\ConfigurationRepository;
use DvsaEntities\Repository\DvlaVehicleRepository;
use DvsaEntities\Repository\MotTestRepository;
use DvsaEntities\Repository\VehicleRepository;
use DvsaMotApi\Service\CertificateExpiryService;
use PHPUnit_Framework_MockObject_MockObject as MockObj;

/**
 * Class CertificateExpiryServiceTest.
 */
class CertificateExpiryServiceTest extends AbstractServiceTestCase
{
    const TEST_CALENDAR_MONTHS_ALLOWED_TO_POST_DATE = 1;
    const YEARS_BEFORE_FIRST_TEST_IS_DUE            = 3;

    /**
     * @var AuthorisationServiceInterface|MockObj
     */
    protected $authorisationService;

    /**
     * @var  MotTestRepository|MockObj
     */
    protected $motTestRepository;

    /**
     * @var  VehicleRepository|MockObj
     */
    protected $vehicleRepository;

    /**
     * @var  ConfigurationRepository|MockObj
     */
    protected $configurationRepository;

    /**
     * @var ParamObfuscator
     */
    protected $paramObfuscator;

    /**
     * @var string
     */
    protected $obfuscatedId;

    /**
     * @var int
     */
    protected $deobfuscatedId;

    public function setUp()
    {
        $appTestConfig = include getcwd() . '/test/test.config.php';
        Bootstrap::init($appTestConfig);

        $this->configurationRepository = \DvsaCommonTest\TestUtils\XMock::of(ConfigurationRepository::class);
        $this->motTestRepository       = XMock::of(MotTestRepository::class);
        $this->vehicleRepository       = XMock::of(VehicleRepository::class);
        $this->dvlaVehicleRepository   = XMock::of(DvlaVehicleRepository::class);
        $this->authorisationService    = $this->getMockAuthorizationService();
        $this->paramObfuscator         = Bootstrap::getServiceManager()->get(ParamObfuscator::class);

        $this->obfuscatedId   = 'NmZkNTJjZTEyMWQzNWQ0ZDc1ZTZlZDcwMGQ0MzkyMjFmOGM1Yjc4MTIwMWE5NzJmMTQxYzczYjE0ZGYxMmM4Y0ppSHBhSnZhMkt1bUdzNUhUSUt0Y29yeG56Z0tzNXdSMVdvS1NIYUFjd28';
        $this->deobfuscatedId = '1';
    }

    public function testCheckVehicleWithNoTestHavingExpiryDate()
    {
        //given
        $date = DateUtils::toDate('2012-05-30');

        $this->setupMotTestRepositoryMockReturnsLastCertificateExpiryDate(null);

        $vehicle = new Vehicle();
        $vehicle->setFirstUsedDate($date);
        $this->setupVehicleRepositoryMockReturnsVehicle($vehicle);
        $this->setupConfigurationRepositoryMockFindValueForNoTestHavingExpiryDate();

        $certificateExpiryService = new CertificateExpiryService(
            new TestDateTimeHolder($date),
            $this->motTestRepository,
            $this->vehicleRepository,
            $this->dvlaVehicleRepository,
            $this->configurationRepository,
            $this->authorisationService,
            $this->paramObfuscator
        );

        // when
        $checkExpiryResults = $certificateExpiryService->getExpiryDetailsForVehicle($this->obfuscatedId);

        // then
        $this->assertEquals(false, $checkExpiryResults['previousCertificateExists']);
        $this->assertEquals('2015-05-29', $checkExpiryResults['expiryDate']);
        $this->assertEquals('2015-04-30', $checkExpiryResults['earliestTestDateForPostdatingExpiryDate']);
        $this->assertEquals(true, $checkExpiryResults['isEarlierThanTestDateLimit']);
    }

    public function testCheckVehicleWhenPassingTestingDate()
    {
        //given
        $dateInvalid = DateUtils::toDate('2012-05-29');
        $date        = DateUtils::toDate('2012-05-30');

        $this->setupMotTestRepositoryMockReturnsLastCertificateExpiryDate(null);

        $vehicle = new Vehicle();
        $vehicle->setFirstUsedDate($date);
        $this->setupVehicleRepositoryMockReturnsVehicle($vehicle);
        $this->setupConfigurationRepositoryMockFindValueForNoTestHavingExpiryDate();

        $certificateExpiryService = new CertificateExpiryService(
            new TestDateTimeHolder($dateInvalid),
            $this->motTestRepository,
            $this->vehicleRepository,
            $this->dvlaVehicleRepository,
            $this->configurationRepository,
            $this->authorisationService,
            $this->paramObfuscator
        );

        // when
        // vehicleId = 3
        $obfuscatedVehicleId = 'MGI2OTgzMGI5MjFiMWJhMDViN2Y1M2EzYWQzMzc2ZWVhZmUwYTVkOTczZGQxNjU2OGQ5MzQyNWNhOWU3ZjY2N3lRMFNnQjhVZDV6TExyS0RtODR2R1lSNG4wZDZGazI3NHh0ZS96WGs4K009';
        $checkExpiryResults  = $certificateExpiryService->getExpiryDetailsForVehicle($obfuscatedVehicleId, $date);

        // then
        $this->assertEquals(false, $checkExpiryResults['previousCertificateExists']);
        $this->assertEquals('2015-05-29', $checkExpiryResults['expiryDate']);
        $this->assertEquals('2015-04-30', $checkExpiryResults['earliestTestDateForPostdatingExpiryDate']);
        $this->assertEquals(true, $checkExpiryResults['isEarlierThanTestDateLimit']);
    }

    public function testCheckVehicleWithNoFirstUsedDate()
    {
        //given
        $dateInvalid = DateUtils::toDate('2012-05-29');
        $date        = DateUtils::toDate('2012-05-30');

        $this->setupMotTestRepositoryMockReturnsLastCertificateExpiryDate(null);

        // not not set firstUsedDate on vehicle
        $vehicle = new Vehicle();
        $this->setupVehicleRepositoryMockReturnsVehicle($vehicle);

        $certificateExpiryService = new CertificateExpiryService(
            new TestDateTimeHolder($dateInvalid),
            $this->motTestRepository,
            $this->vehicleRepository,
            $this->dvlaVehicleRepository,
            $this->configurationRepository,
            $this->authorisationService,
            $this->paramObfuscator
        );

        // when
        // vehicleId = 3
        $obfuscatedVehicleId = 'MGI2OTgzMGI5MjFiMWJhMDViN2Y1M2EzYWQzMzc2ZWVhZmUwYTVkOTczZGQxNjU2OGQ5MzQyNWNhOWU3ZjY2N3lRMFNnQjhVZDV6TExyS0RtODR2R1lSNG4wZDZGazI3NHh0ZS96WGs4K009';
        $checkExpiryResults  = $certificateExpiryService->getExpiryDetailsForVehicle($obfuscatedVehicleId, $date);

        $this->assertFalse($checkExpiryResults['previousCertificateExists']);
    }

    public function testCheckVehicleWithTodayAfterEarliestDateAllowed()
    {
        //given
        $currentDate = (new \DateTime())->setDate(2014, 4, 20);
        $expiryDate  = (new \DateTime())->setDate(2014, 5, 10);
        $this->setupMotTestRepositoryMockReturnsLastCertificateExpiryDate($expiryDate);

        $certificateExpiryService = new CertificateExpiryService(
            new TestDateTimeHolder($currentDate),
            $this->motTestRepository,
            $this->vehicleRepository,
            $this->dvlaVehicleRepository,
            $this->configurationRepository,
            $this->authorisationService,
            $this->paramObfuscator
        );

        // when
        $checkExpiryResults = $certificateExpiryService->getExpiryDetailsForVehicle($this->obfuscatedId);

        // then
        $this->assertEquals(true, $checkExpiryResults['previousCertificateExists']);
        $this->assertEquals('2014-05-10', $checkExpiryResults['expiryDate']);
        $this->assertEquals('2014-04-11', $checkExpiryResults['earliestTestDateForPostdatingExpiryDate']);
        $this->assertEquals(false, $checkExpiryResults['isEarlierThanTestDateLimit']);
    }

    public function testCheckVehicleWithTodayBeforeEarliestDateAllowed()
    {

        //given
        $currentDate = (new \DateTime())->setDate(2014, 4, 2);
        $expiryDate  = (new \DateTime())->setDate(2014, 5, 10);
        $this->setupMotTestRepositoryMockReturnsLastCertificateExpiryDate($expiryDate);

        $certificateExpiryService = new CertificateExpiryService(
            new TestDateTimeHolder($currentDate),
            $this->motTestRepository,
            $this->vehicleRepository,
            $this->dvlaVehicleRepository,
            $this->configurationRepository,
            $this->authorisationService,
            $this->paramObfuscator
        );

        // when
        $checkExpiryResults = $certificateExpiryService->getExpiryDetailsForVehicle($this->obfuscatedId);

        // then
        $this->assertEquals(true, $checkExpiryResults['previousCertificateExists']);
        $this->assertEquals('2014-05-10', $checkExpiryResults['expiryDate']);
        $this->assertEquals('2014-04-11', $checkExpiryResults['earliestTestDateForPostdatingExpiryDate']);
        $this->assertEquals(true, $checkExpiryResults['isEarlierThanTestDateLimit']);
    }

    protected function setupMotTestRepositoryMockReturnsLastCertificateExpiryDate($date)
    {
        $this->motTestRepository
            ->expects($this->any())
            ->method("findLastCertificateExpiryDate")
            ->withAnyParameters()
            ->will($this->returnValue($date));
    }

    protected function setupVehicleRepositoryMockReturnsVehicle($vehicle)
    {
        $this->vehicleRepository
            ->expects($this->any())
            ->method("find")
            ->withAnyParameters()
            ->will($this->returnValue($vehicle));
    }

    protected function setupConfigurationRepositoryMockFindValuePostDateMonths()
    {
        $this->configurationRepository
            ->expects($this->once())
            ->method("getValue")
            ->will($this->returnValue(self::TEST_CALENDAR_MONTHS_ALLOWED_TO_POST_DATE));
    }

    protected function setupConfigurationRepositoryMockFindValueForNoTestHavingExpiryDate()
    {
        $this->configurationRepository->expects($this->at(0))
            ->method("getValue")
            ->will($this->returnValue(self::YEARS_BEFORE_FIRST_TEST_IS_DUE));
    }
}
