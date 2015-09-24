<?php
namespace UserApiTest\Service;

use DvsaCommon\Enum\AuthorisationForTestingMotStatusCode;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\MockHandler;
use DvsaEntities\Entity\AuthorisationForTestingMot;
use DvsaEntities\Entity\AuthorisationForTestingMotStatus;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\VehicleClass;
use NotificationApi\Service\NotificationService;
use PersonApi\Dto\MotTestingAuthorisationCollector;
use PersonApi\Service\PersonalAuthorisationForMotTestingService;
use PersonApi\Service\Validator\PersonalAuthorisationForMotTestingValidator;

/**
 * Class PersonalAuthorisationForMotTestingServiceTest
 *
 * @package NotificationApiTest\Service
 */
class PersonalAuthorisationForMotTestingServiceTest extends AbstractServiceTestCase
{
    const PERSON_ID = 1;

    private $authStatusRepo;
    private $authRepo;
    private $mocks;
    private $entityManager;
    /** @var $person Person  */
    private $person;

    public function setUp()
    {
        $this->authStatusRepo = $this->getRepositoryMock();
        $this->authRepo = $this->getRepositoryMock();
        $this->mocks = $this->getMocksForPersonalAuthorisationService();
        $this->entityManager = new MockHandler($this->mocks['entityManagerMock'], $this);
        $this->person = (new Person())->setId(self::PERSON_ID);
        $this
            ->entityManager->find()->with(Person::class, self::PERSON_ID)->will($this->returnValue($this->person));
    }

    public function test_updatePersonalTestingAuthorisationGroup_groupASuccess_shouldBeOk()
    {
        $authorisations = $this->createAuthorisationsForVehicleClasses([1, 2]);

        $this->runTest_updatePersonalTestingAuthorisationGroup_authorisationListPass(
            PersonalAuthorisationForMotTestingService::GROUP_A_VEHICLE,
            $authorisations
        );
    }

    public function test_updatePersonalTestingAuthorisationGroup_groupBSuccess_shouldBeOk()
    {
        $authorisations = $this->createAuthorisationsForVehicleClasses([3, 4, 5, 7]);
        $this->person->setAuthorisationsForTestingMot($authorisations);
        $this->runTest_updatePersonalTestingAuthorisationGroup_authorisationListPass(
            PersonalAuthorisationForMotTestingService::GROUP_B_VEHICLE,
            $authorisations
        );
    }

    public function test_updatePersonalTestingAuthorisationGroup_trainingFailedGroupA_shouldBeOk()
    {
        $authorisations = $this->createAuthorisationsForVehicleClasses([1, 2]);
        $this->runTest_updatePersonalTestingAuthorisationGroup_authorisationListFail(
            PersonalAuthorisationForMotTestingService::GROUP_A_VEHICLE,
            $authorisations
        );
    }

    public function test_updatePersonalTestingAuthorisationGroup_trainingFailedGroupB_shouldBeOk()
    {
        $authorisations = $this->createAuthorisationsForVehicleClasses([3, 4, 5, 7]);
        $this->runTest_updatePersonalTestingAuthorisationGroup_authorisationListFail(
            PersonalAuthorisationForMotTestingService::GROUP_B_VEHICLE,
            $authorisations
        );
    }

    public function test_getPersonalTestingAuthorisation_shouldBeOk()
    {
        $authorisations = $this->createAuthorisationsForVehicleClasses([1, 2, 3, 4, 5, 7]);
        $this->person->setAuthorisationsForTestingMot($authorisations);
        $service = $this->constructPersonalAuthorisationServiceWithMocks($this->mocks);

        $result = $service->getPersonalTestingAuthorisation(self::PERSON_ID);

        $this->assertInstanceOf(MotTestingAuthorisationCollector::class, $result);
    }

    private function runTest_updatePersonalTestingAuthorisationGroup_authorisationListFail($group, $authorisations)
    {
        $this->person->setAuthorisationsForTestingMot($authorisations);
        $service = $this->constructPersonalAuthorisationServiceWithMocks($this->mocks);

        $result = $service->updatePersonalTestingAuthorisationGroup(
            self::PERSON_ID,
            ['result' => 0, 'group' => $group]
        );

        $this->assertInstanceOf(MotTestingAuthorisationCollector::class, $result);
    }

    private function runTest_updatePersonalTestingAuthorisationGroup_authorisationListPass($group, $authorisations)
    {
        $this->person->setAuthorisationsForTestingMot($authorisations);
        $this->mockUpdatingAuthorisations($authorisations);
        $service = $this->constructPersonalAuthorisationServiceWithMocks($this->mocks);

        $result = $service->updatePersonalTestingAuthorisationGroup(
            self::PERSON_ID,
            ['result' => 1, 'group' => $group]
        );

        $this->assertInstanceOf(MotTestingAuthorisationCollector::class, $result);
    }

    private function mockUpdatingAuthorisations($authorisations)
    {
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
            ->method('findOneBy')->with(['code' => AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED])
            ->will(
                $this->returnValue(
                    (new AuthorisationForTestingMotStatus)->setCode(
                        AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED
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
            new PersonalAuthorisationForMotTestingValidator()
        );
    }

    private function getMocksForPersonalAuthorisationService()
    {
        return [
            'entityManagerMock' => $this->getMockEntityManager(),
            'notificationServiceMock' => $this->getMockWithDisabledConstructor(NotificationService::class),
        ];
    }
}
