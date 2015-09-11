<?php

namespace UserApiTest\Service;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\AuthorisationService;
use DvsaCommon\Enum\AuthorisationForTestingMotStatusCode;
use DvsaCommon\Enum\VehicleClassCode;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\MockHandler;
use DvsaEntities\Entity\AuthorisationForTestingMot;
use DvsaEntities\Entity\AuthorisationForTestingMotStatus;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\VehicleClass;
use DvsaEventApi\Service\EventService;
use NotificationApi\Service\NotificationService;
use PersonApi\Dto\MotTestingAuthorisationCollector;
use PersonApi\Service\PersonalAuthorisationForMotTestingService;
use PersonApi\Service\PersonService;
use PersonApi\Service\Validator\PersonalAuthorisationForMotTestingValidator;
use DvsaAuthentication\Identity;

/**
 * Unit tests for PersonalAuthorisationForMotTestingService
 */
class PersonalAuthorisationForMotTestingServiceTest extends AbstractServiceTestCase
{
    const PERSON_ID = 1;

    private static $groupA = [VehicleClassCode::CLASS_1, VehicleClassCode::CLASS_2];
    private static $groupB = [
        VehicleClassCode::CLASS_3, VehicleClassCode::CLASS_4, VehicleClassCode::CLASS_5, VehicleClassCode::CLASS_7
    ];

    private $authStatusRepo;
    private $authRepo;
    private $mocks;
    /** @var EntityManager*/
    private $entityManager;
    /** @var $person Person */
    private $person;

    public function setUp()
    {
        $this->authStatusRepo = $this->getRepositoryMock();
        $this->authRepo = $this->getRepositoryMock();
        $this->mocks = $this->getMocksForPersonalAuthorisationService();
        $this->entityManager = new MockHandler($this->mocks['entityManagerMock'], $this);
        $this->person = (new Person())->setId(self::PERSON_ID);
        $this->entityManager->find()->with(Person::class, self::PERSON_ID)->will($this->returnValue($this->person));
        $this->mocks['authorisationServiceMock']
            ->expects($this->any())
            ->method('getIdentity')
            ->willReturn($this->mocks['identityMock']);
        $this->mocks['personServiceMock']
            ->expects($this->any())
            ->method('getPersonById')
            ->willReturn($this->person);
    }

    /**
     * @dataProvider getAllStatuses
     */
    public function test_updatePersonalTestingAuthorisationGroup_groupA_allStatuses_shouldBeOk($status)
    {
        $this->markTestSkipped('Mocking issue');
        $authorisations = $this->createAuthorisationsForVehicleClasses([1, 2], $status);

        $this->runTest_updatePersonalTestingAuthorisationGroup_authorisationListPass(
            PersonalAuthorisationForMotTestingService::GROUP_A_VEHICLE,
            $authorisations,
            $status
        );
    }

    public function test_updatePersonalTestingAuthorisationGroup_groupASuccess_shouldBeOk()
    {
        $this->markTestSkipped('Mocking issue');
        $authorisations = $this->createAuthorisationsForVehicleClasses(self::$groupA);

        $this->runTest_updatePersonalTestingAuthorisationGroup_authorisationListPass(
            PersonalAuthorisationForMotTestingService::GROUP_A_VEHICLE,
            $authorisations
        );
    }

    public function test_updatePersonalTestingAuthorisationGroup_groupBSuccess_shouldBeOk()
    {
        $this->markTestSkipped('Mocking issue');
        $authorisations = $this->createAuthorisationsForVehicleClasses(self::$groupB);
        $this->person->setAuthorisationsForTestingMot($authorisations);
        $this->runTest_updatePersonalTestingAuthorisationGroup_authorisationListPass(
            PersonalAuthorisationForMotTestingService::GROUP_B_VEHICLE,
            $authorisations
        );
    }

    public function test_updatePersonalTestingAuthorisationGroup_trainingFailedGroupA_shouldBeOk()
    {
        $this->markTestSkipped('Mocking issue');
        $authorisations = $this->createAuthorisationsForVehicleClasses(self::$groupA);
        $this->runTest_updatePersonalTestingAuthorisationGroup_authorisationListFail(
            PersonalAuthorisationForMotTestingService::GROUP_A_VEHICLE,
            $authorisations
        );
    }

    public function test_updatePersonalTestingAuthorisationGroup_trainingFailedGroupB_shouldBeOk()
    {
        $this->markTestSkipped('Mocking issue');
        $authorisations = $this->createAuthorisationsForVehicleClasses(self::$groupB);
        $this->runTest_updatePersonalTestingAuthorisationGroup_authorisationListFail(
            PersonalAuthorisationForMotTestingService::GROUP_B_VEHICLE,
            $authorisations
        );
    }

    public function test_getPersonalTestingAuthorisation_shouldBeOk()
    {
        $authorisations = $this->createAuthorisationsForVehicleClasses(array_merge(self::$groupA, self::$groupB));
        $this->person->setAuthorisationsForTestingMot($authorisations);
        $service = $this->constructPersonalAuthorisationServiceWithMocks($this->mocks);

        $result = $service->getPersonalTestingAuthorisation(self::PERSON_ID);

        $this->assertInstanceOf(MotTestingAuthorisationCollector::class, $result);
    }

    public function getAllStatuses()
    {
        return [
            [AuthorisationForTestingMotStatusCode::UNKNOWN],
            [AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED],
            [AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED],
            [AuthorisationForTestingMotStatusCode::QUALIFIED],
            [AuthorisationForTestingMotStatusCode::SUSPENDED],
        ];
    }

    private function runTest_updatePersonalTestingAuthorisationGroup_authorisationListFail(
        $group,
        $authorisations,
        $status = AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED
    ) {
        $this->person->setAuthorisationsForTestingMot($authorisations);
        $this->mockUpdatingAuthorisations($authorisations, $status);
        $service = $this->constructPersonalAuthorisationServiceWithMocks($this->mocks);

        $result = $service->updatePersonalTestingAuthorisationGroup(
            self::PERSON_ID,
            ['result' => $status, 'group' => $group]
        );

        $this->assertInstanceOf(MotTestingAuthorisationCollector::class, $result);
    }

    private function runTest_updatePersonalTestingAuthorisationGroup_authorisationListPass(
        $group,
        $authorisations,
        $status = AuthorisationForTestingMotStatusCode::QUALIFIED
    ) {
        $this->person->setAuthorisationsForTestingMot($authorisations);
        $this->mockUpdatingAuthorisations($authorisations, $status);
        $service = $this->constructPersonalAuthorisationServiceWithMocks($this->mocks);

        $result = $service->updatePersonalTestingAuthorisationGroup(
            self::PERSON_ID,
            ['result' => $status, 'group' => $group]
        );

        $this->assertInstanceOf(MotTestingAuthorisationCollector::class, $result);
    }

    private function mockUpdatingAuthorisations(
        $authorisations,
        $status = AuthorisationForTestingMotStatusCode::QUALIFIED
    ) {
        $n = count($authorisations);
        while ($n--) {
            $this->entityManager
                ->getRepository(AuthorisationForTestingMotStatus::class)
                ->will($this->returnValue($this->authStatusRepo));
            $this->entityManager->next('persist');
        }
        $this->entityManager->next('flush');

        $this->authStatusRepo
            ->expects($this->any())
            ->method('findOneBy')->with(['code' => $status])
            ->will(
                $this->returnValue(
                    (new AuthorisationForTestingMotStatus)->setCode(
                        $status
                    )
                )
            );
    }

    private function createAuthorisationsForVehicleClasses(
        array $classes,
        $code = AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED
    ) {
        $result = [];
        foreach ($classes as $class) {
            $auth = new AuthorisationForTestingMot;
            $auth->setVehicleClass((new VehicleClass())->setCode($class));
            $auth->setStatus((new AuthorisationForTestingMotStatus())->setCode($code));
            $result[] = $auth;
        }

        return $result;
    }

    /**
     * @param array $mocks
     *
     * @return PersonalAuthorisationForMotTestingService
     */
    private function constructPersonalAuthorisationServiceWithMocks($mocks)
    {
        return new PersonalAuthorisationForMotTestingService(
            $mocks['entityManagerMock'],
            $mocks['notificationServiceMock'],
            new PersonalAuthorisationForMotTestingValidator(),
            $mocks['authorisationServiceMock'],
            $mocks['eventServiceMock'],
            $mocks['personServiceMock']
        );
    }

    private function getMocksForPersonalAuthorisationService()
    {
        return [
            'entityManagerMock'        => $this->getMockEntityManager(),
            'notificationServiceMock'  => $this->getMockWithDisabledConstructor(NotificationService::class),
            'authorisationServiceMock' => $this->getMockWithDisabledConstructor(AuthorisationService::class),
            'eventServiceMock'         => $this->getMockWithDisabledConstructor(EventService::class),
            'personServiceMock'        => $this->getMockWithDisabledConstructor(PersonService::class),
            'identityMock'             => $this->getMockWithDisabledConstructor(Identity::class)
        ];
    }
}
