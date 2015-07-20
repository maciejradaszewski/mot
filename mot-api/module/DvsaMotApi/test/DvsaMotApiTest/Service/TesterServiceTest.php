<?php
namespace DvsaMotApiTest\Service;

use DvsaCommon\Constants\Role as RoleConstants;
use DvsaCommon\Enum\AuthorisationForTestingMotStatusCode;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\MockHandler;
use DvsaEntities\Entity\AuthorisationForAuthorisedExaminer;
use DvsaEntities\Entity\AuthorisationForTestingMot;
use DvsaEntities\Entity\AuthorisationForTestingMotStatus;
use DvsaEntities\Entity\Organisation;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\Site;
use DvsaEntities\Entity\VehicleClass;
use DvsaEntities\Repository\MotTestRepository;
use DvsaEntities\Repository\PersonRepository;
use DvsaEntities\Repository\SiteRepository;
use DvsaMotApi\Service\TesterService;
use UserApi\SpecialNotice\Service\SpecialNoticeService;
use UserFacade\Role;

/**
 * Class TesterServiceTest
 */
class TesterServiceTest extends AbstractServiceTestCase
{
    public function testGetTesterData()
    {
        $this->markTestSkipped('phils advice. will come back to this because of findByOne magic call.');
        //given
        $username = 'tester1';
        $testerId = 4;

        $vts = $this->getTestVts();

        $mocks = $this->getMocksForTesterService();
        $tester = $this->getTestTester($vts, $mocks['mockUserFacade'], $mocks['mockAuthorisationService']);

        $hydratorTesterData = $this->getHydratorTesterReturnData($username);
        $hydratorVtsData = ['id' => 1, 'roles' => []];
        $expectedTesterData = $this->getExpectedTesterData($username);
        //mocked

        $this->setupMockForSingleCall($mocks['mockRepository'], 'get', $tester, $testerId);
        $this->setupExtractTesterMockHydrator(
            $mocks['mockHydrator'],
            $tester,
            $hydratorTesterData,
            $vts,
            $hydratorVtsData
        );

        $testerService = $this->constructTesterServiceWithMocks($mocks);
        //when then
        $this->assertEquals($expectedTesterData, $testerService->getTesterData($testerId));
    }

    public function testGetTesterByPersonIdData()
    {
        $this->markTestSkipped('phils advice. Will come back to this test due to findByOne magic method issue.');
        //given
        $username = 'tester1';
        $personId = 1;

        $vts = $this->getTestVts();

        $mocks = $this->getMocksForTesterService();
        $tester = $this->getTestTester($vts, $mocks['mockUserFacade'], $mocks['mockAuthorisationService']);

        $hydratorTesterData = $this->getHydratorTesterReturnData($username);
        $hydratorVtsData = ['id' => 1, 'roles' => []];
        $expectedTesterData = $this->getExpectedTesterData($username);
        //mocked
        $this->setupMockForSingleCall($mocks['mockRepository'], 'find', $tester, $personId);
        $this->setupExtractTesterMockHydrator(
            $mocks['mockHydrator'],
            $tester,
            $hydratorTesterData,
            $vts,
            $hydratorVtsData
        );

        $testerService = $this->constructTesterServiceWithMocks($mocks);
        //when then
        $this->assertEquals($expectedTesterData, $testerService->getTesterDataByUserId($personId));
    }

    /**
     * This is the data provided for the test
     *
     * @dataProvider testerVerifyIsActiveData
     */
    public function testVerifyAndApplyTesterIsActive($isTesterPreviouslyActive, $isTesterOverdue)
    {
        $this->markTestSkipped();
        //given
        $personId = 1;
        $vts = $this->getTestVts();

        $mocks = $this->getMocksForTesterService();
        $tester = $this->getTestTester($vts, $mocks['mockUserFacade'], $mocks['mockAuthorisationService'], $isTesterPreviouslyActive);

        $this->setupMockForSingleCall($mocks['mockRepository'], 'find', $tester, $personId);
        $mocks['mockSpecialNoticesService']
            ->expects($this->once())
            ->method('isUserOverdue')
            ->with($tester)
            ->will($this->returnValue($isTesterOverdue));
        $isActive = !$isTesterOverdue;
        if ($isActive !== $isTesterPreviouslyActive) {
            $mocks['mockEntityManagerHandler']
                ->next('persist')
                ->with($tester);
            $mocks['mockEntityManagerHandler']
                ->next('flush');
        }

        $testerService = $this->constructTesterServiceWithMocks($mocks);
        //when then
        $this->assertEquals(
            $isActive !== $isTesterPreviouslyActive,
            $testerService->verifyAndApplyTesterIsActiveByUserId($personId)
        ); //test if function returns true upon actually making changes any role
    }

    public static function testerVerifyIsActiveData()
    {
        return [
            [
                'previouslyActive' => true,
                'overdue' => false,
            ],
            [
                'previouslyActive' => false,
                'overdue' => false,
            ],
            [
                'previouslyActive' => true,
                'overdue' => true,
            ],
            [
                'previouslyActive' => false,
                'overdue' => true,
            ],
        ];
    }

    public function getHydratorTesterReturnData($username)
    {
        return [
            'username' => $username,
            'vehicleTestingStations' => [],
            'roles' => [],
        ];
    }

    public function getExpectedTesterData($username)
    {
        return [
            'username' => $username,
            'roles' => ["TESTER-ACTIVE"],
            'vtsSites' => [
                [
                    'id' => 1,
                    'address' => null,
                    'slots' => 1,
                    'slotsWarning' => 30,
                    'aeId' => null
                ],
            ],
            'user' => [
                'displayName' => ' ' // @todo: temporary, I just need this to work.
            ],
            'authorisationsForTestingMot' => [
                0 => [
                    'id' => '',
                    'vehicleClassCode' => '1',
                    'statusCode' => AuthorisationForTestingMotStatusCode::QUALIFIED
                ]
            ],
        ];
    }

    private function getTestTester($vts, $mockUserFacade, $mockAuthorisationService, $isActive = true)
    {
        $tester = new Person();
        if ($isActive) {
            $mockUserFacade
                ->expects($this->any())
                ->method('getRoles')
                ->will($this->returnValue([Role::createRole(RoleConstants::TESTER_ACTIVE)]));

            $mockAuthorisationService
                ->expects($this->any())
                ->method('personHasRole')
                ->will($this->returnValue(true));
        } else {
            $mockAuthorisationService
                ->expects($this->any())
                ->method('personHasRole')
                ->will($this->returnValue(false));
        }
        $auth = (new AuthorisationForTestingMot())
            ->setVehicleClass((new VehicleClass())->setCode("1"))
            ->setStatus(
                (new AuthorisationForTestingMotStatus())
                    ->setCode(AuthorisationForTestingMotStatusCode::QUALIFIED)
            );
        $tester->addVehicleTestingStation($vts);
        $tester->setAuthorisationsForTestingMot([$auth]);

        return $tester;
    }

    public function getTestVts()
    {
        $ae = new AuthorisationForAuthorisedExaminer();

        $vehicleTestingStation = new Site();
        $org = new Organisation();
        $org->setSlotBalance(1);
        $org->setSlotsWarning(30);
        $org->setId(9);
        $org->setAuthorisedExaminer($ae);

        $vehicleTestingStation->setOrganisation($org);

        return $vehicleTestingStation;
    }

    public function setupExtractTesterMockHydrator(&$mockHydrator, $tester, $testerData, $vts, $vtsData)
    {
        $mockHydratorHandler = new MockHandler($mockHydrator, $this);
        $mockHydratorHandler
            ->next('extract')
            ->with($tester)
            ->will($this->returnValue($testerData));
        $mockHydratorHandler
            ->next('extract')
            ->will($this->returnValue([]));
        $mockHydratorHandler
            ->next('extract')
            ->with($vts)
            ->will($this->returnValue($vtsData));

        return $mockHydrator;
    }

    protected function getMocksForTesterService()
    {
        $mockAuthRepository = $this->getMockRepository();
        $mockAuthRepository->expects($this->any())
            ->method('activateSuspendedAuthorisationsForPerson')
            ->will($this->returnValue(true));
        $mockAuthRepository->expects($this->any())
            ->method('suspendQualifiedAuthorisationsForPerson')
            ->will($this->returnValue(true));
        $motTestRepository = $this->getMockRepository(MotTestRepository::class);
        $mockRepository = $this->getMockRepository(PersonRepository::class);
        $mockSiteRepository = $this->getMockRepository(SiteRepository::class);
        $mockHydrator = $this->getMockHydrator();
        $mockSpecialNoticesService = $this->getMockWithDisabledConstructor(
            SpecialNoticeService::class
        );
        $mockAuthorisationService = $this->getMockWithDisabledConstructor(
            \DvsaAuthorisation\Service\AuthorisationServiceInterface::class
        );
        $this->setupMockForCalls($mockAuthorisationService, 'isGranted', true);

        $mockEntityManager = $this->getMockEntityManager();
        $mockEntityManagerHandler = new MockHandler($mockEntityManager, $this);
        $mockEntityManagerHandler->next('getRepository')->will($this->returnValue($mockRepository));
        $mockEntityManagerHandler->next('getRepository')->will($this->returnValue($motTestRepository));
        $mockEntityManagerHandler->next('getRepository')->will($this->returnValue($mockAuthRepository));
        $mockIdentityProviderService = $this->getMockWithDisabledConstructor(\DvsaCommon\Auth\MotIdentityProviderInterface::class);
        $mockRoleProviderService = $this->getMockWithDisabledConstructor(\DvsaAuthorisation\Service\RoleProviderService::class);
        return [
            'mockEntityManagerHandler' => $mockEntityManagerHandler,
            'mockEntityManager' => $mockEntityManager,
            'mockHydrator' => $mockHydrator,
            'mockRepository' => $mockRepository,
            'mockAuthorisationService' => $mockAuthorisationService,
            'mockSpecialNoticesService' => $mockSpecialNoticesService,
            'mockUserFacade' => $this->getMockUserFacade(),
            'mockRoleProviderService' => $mockRoleProviderService,
            'mockSiteRepository' => $mockSiteRepository,
            'mockIdentityProvicerService' => $mockIdentityProviderService,
        ];
    }

    protected function constructTesterServiceWithMocks($mocks)
    {
        return new TesterService(
            $mocks['mockEntityManager'],
            $mocks['mockHydrator'],
            $mocks['mockAuthorisationService'],
            $mocks['mockSpecialNoticesService'],
            $mocks['mockRoleProviderService'],
            $mocks['mockIdentityProviderService'],
            $mocks['mockSiteRepository']
        );
    }
}
