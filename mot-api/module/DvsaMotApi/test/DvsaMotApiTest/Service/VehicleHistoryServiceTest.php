<?php

namespace DvsaMotApiTest\Service;

use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Date\DateUtils;
use DvsaCommon\Dto\Vehicle\History\VehicleHistoryItemDto;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\Date\TestDateTimeHolder;
use DvsaCommonTest\TestUtils\ArgCapture;
use DvsaCommonTest\TestUtils\TestCasePermissionTrait;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\MotTestStatus;
use DvsaEntities\Entity\MotTestType;
use DvsaEntities\Repository\ConfigurationRepository;
use DvsaEntities\Repository\MotTestRepository;
use DvsaMotApi\Service\MotTestService;
use DvsaMotApi\Service\VehicleHistoryService;
use PHPUnit_Framework_MockObject_MockObject;

/**
 * Unit test for MotTestService.
 */
class VehicleHistoryServiceTest extends AbstractMotTestServiceTest
{
    use TestCasePermissionTrait;

    const VEHICLE_ID = 12345;

    public function setUp()
    {
        $this->getMocksForVehicleHistoryService();
    }

    public function testFindHistoricalTestsForVehicleSinceGivenVehicleIdAndStartDateShouldRelayTheThemAsIs()
    {
        $captureVehicleId = ArgCapture::create();
        $captureStartDate = ArgCapture::create();
        $testStartDate    = new \DateTime();

        $this->mockMotTestRepository
            ->expects($this->any())
            ->method('findHistoricalTestsForVehicle')
            ->with($captureVehicleId(), $captureStartDate())
            ->will($this->returnValue([]));

        $service = $this->constructVehicleHistoryServiceWithMocks();
        $service->findHistoricalTestsForVehicleSince(self::VEHICLE_ID, $testStartDate);

        $this->assertEquals(self::VEHICLE_ID, $captureVehicleId->get(), "Relayed vehicle id mismatch!");
        $this->assertEquals($testStartDate, $captureStartDate->get(), "Relayed start date mismatch");
    }

    public function testFindHistoricalTestsForVehicleSinceGivenVehicleIdAndNoStartDateShouldRelayTheSameId()
    {
        $captureVehicleId        = ArgCapture::create();
        $maxDefaultHistoryLength = MotTestService::CONFIG_PARAM_MAX_VISIBLE_VEHICLE_TEST_HISTORY_IN_MONTHS;

        $testDate = DateUtils::toDate('2012-09-30');

        $this->mockMotTestRepository
            ->expects($this->any())
            ->method('findHistoricalTestsForVehicle')
            ->with($captureVehicleId(), null)
            ->will($this->returnValue([]));

        $this->mockMethod($this->mockConfigurationRepository, "getValue", null, $maxDefaultHistoryLength);
        $this->mockMethod($this->mockAuthService, 'isGranted', null, true);

        $service = $this->constructVehicleHistoryServiceWithMocks();
        $service->findHistoricalTestsForVehicleSince(self::VEHICLE_ID);

        DateUtils::subtractCalendarMonths($testDate, $maxDefaultHistoryLength);
        $this->assertEquals(self::VEHICLE_ID, $captureVehicleId->get(), "Relayed vehicle id mismatch!");
    }

    public function testFindHistoricalTestsForVehicleSinceAsActiveTesterShouldReturnOnlyFirstNormalAndRetestMotTestMarkedAsEditable()
    {
        $this->mockIsGranted($this->mockAuthService, [PermissionInSystem::CERTIFICATE_REPLACEMENT]);

        $this->commonAllowEditCountTest(1, $this->createMotTests());
    }

    public function testFindHistoricalTestsForVehicleSinceAsInactiveTesterShouldReturnTestsWithOverdueDateSetAsUneditable()
    {
        $listOfMotTests = [
            (new MotTest())
                ->setStatus($this->createMotTestStatus(MotTestStatusName::PASSED))
                ->setMotTestType(
                    (new MotTestType())
                        ->setCode(MotTestTypeCode::NORMAL_TEST)
                )
                ->setIssuedDate((new \DateTime())->sub(new \DateInterval('P8D'))),
        ];

        $this->mockIsGranted($this->mockAuthService, [PermissionInSystem::CERTIFICATE_REPLACEMENT]);

        $this->commonAllowEditCountTest(0, $listOfMotTests);
    }

    public function testFindHistoricalTestsForVehicleSinceAsUserHavingCertificateReplacementFullPermissionShouldReturnAllNormalAndRetestMotTestMarkedAsEditable()
    {
        $this->mockMethod($this->mockAuthService, 'isGranted', null, true);

        $this->commonAllowEditCountTest(2, $this->createMotTests());
    }

    protected function commonAllowEditCountTest($expectedCount, $listOfMotTests)
    {
        $now = new \DateTime();

        $this->mockMethod($this->mockMotTestRepository, 'findHistoricalTestsForVehicle', null, $listOfMotTests);

        $service           = $this->constructVehicleHistoryServiceWithMocks();
        $vehicleHistoryDto = $service->findHistoricalTestsForVehicleSince(self::VEHICLE_ID, $now);

        $count = 0;
        foreach ($vehicleHistoryDto->getIterator() as $item) {
            /* @var VehicleHistoryItemDto $item */
            if ($item->isAllowEdit()) {
                $count++;
            }
        }

        $this->assertEquals($expectedCount, $count, "Number of editable Mot tests is incorrect");
    }

    protected function getMocksForVehicleHistoryService()
    {
        $this->mockAuthService = $this->getMockAuthorizationService(false);
        $this->mockMotTestRepository = $this->getMockRepository(MotTestRepository::class);
        $this->mockConfigurationRepository = $this->getMockWithDisabledConstructor(ConfigurationRepository::class);
    }

    protected function constructVehicleHistoryServiceWithMocks() //$mocks)
    {
        $vehicleHistoryService = new VehicleHistoryService(
            $this->mockMotTestRepository,
            $this->mockAuthService,
            $this->mockConfigurationRepository
        );

        XMock::mockClassField($vehicleHistoryService, 'dateTimeHolder', new TestDateTimeHolder(new \DateTime()));

        return $vehicleHistoryService;
    }

    /**
     * @return MotTest[]
     */
    protected function createMotTests()
    {
        return [
            (new MotTest())
                ->setStatus($this->createMotTestStatus(MotTestStatusName::ABANDONED))
                ->setMotTestType(
                    (new MotTestType())
                        ->setCode(MotTestTypeCode::NORMAL_TEST)
                )
                ->setIssuedDate(new \DateTime()),
            (new MotTest())
                ->setStatus($this->createMotTestStatus(MotTestStatusName::PASSED))
                ->setMotTestType(
                    (new MotTestType())
                        ->setCode(MotTestTypeCode::NORMAL_TEST)
                )
                ->setIssuedDate(new \DateTime()),
            (new MotTest())
                ->setStatus($this->createMotTestStatus(MotTestStatusName::PASSED))
                ->setMotTestType(
                    (new MotTestType())
                        ->setCode(MotTestTypeCode::OTHER)
                )
                ->setIssuedDate(new \DateTime()),
        ];
    }

    protected function createMotTestStatus($name)
    {
        $status = XMock::of(MotTestStatus::class);
        $this->mockMethod($status, "getName", null, $name);

        return $status;
    }
}
