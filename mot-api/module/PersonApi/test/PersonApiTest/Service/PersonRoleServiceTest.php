<?php

namespace PersonApiTest\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Model\PersonAuthorization;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\XMock;
use DvsaCommon\Enum\BusinessRoleStatusCode;
use DvsaEntities\Entity\BusinessRoleStatus;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\PersonSystemRole;
use DvsaEntities\Entity\PersonSystemRoleMap;
use DvsaEntities\Entity\Role;
use DvsaEntities\Repository\PermissionToAssignRoleMapRepository;
use DvsaEntities\Repository\PersonRepository;
use DvsaEntities\Repository\PersonSystemRoleMapRepository;
use DvsaEntities\Repository\PersonSystemRoleRepository;
use DvsaEntities\Repository\RbacRepository;
use DvsaEntities\Repository\RoleRepository;
use DvsaMotApi\Helper\RoleEventHelper;
use DvsaMotApi\Helper\RoleNotificationHelper;
use PersonApi\Service\PersonRoleService;

class PersonRoleServiceTest extends AbstractServiceTestCase
{
    const PERSON_ID = 1;
    const PERSON_SYSTEM_ROLE_ID = 42;
    const FAKE_PERSON_SYSTEM_ROLE = 'fakeRole';

    /**
     * Rules for the current logged in user and what the user being managed currently has
     * active = managed user currently has role
     * allowed = manager can manage the role
     * @var array
     */
    private $permissionMap = [
        ['code' => 'A', 'active' => true, 'allowed' => true, 'permission' => 'MANAGE-A'],
        ['code' => 'B', 'active' => true, 'allowed' => false, 'permission' => 'MANAGE-B'],
        ['code' => 'C', 'active' => false, 'allowed' => true, 'permission' => 'MANAGE-C'],
        ['code' => 'D', 'active' => false, 'allowed' => false, 'permission' => 'MANAGE-D']
    ];

    /**
     * @var array
     */
    private $mocks;

    public function testCreate()
    {
        $roleCode = self::FAKE_PERSON_SYSTEM_ROLE;
        $this->fakePersonRepository_find();
        $this->fakePersonSystemRoleRepository_getByName();
        $this->fakePermissionToAssignRoleMapRepository_getPermissionCodeByRoleCode_Defined($roleCode);
        $this->fakeEventHelper_createAssignRoleEvent();
        $this->fakeNotifcationHelper_sendAssignRoleNotification();

        $obj = $this->createMockService();
        $actual = $obj->create(self::PERSON_ID, ['personSystemRoleCode' => $roleCode]);

        $this->assertInstanceOf('DvsaEntities\Entity\PersonSystemRoleMap', $actual);
    }

    public function testAssignRole_NotExists()
    {
        $this->fakePersonSystemRoleMapRepository_findByPersonAndSystemRole_Null();
        $this->fakeBusinessRoleStatusRepository_findOneBy();
        $this->fakePersonSystemRoleMapRepository_save();
        $personMock = $this->createPersonMock();
        $personSystemRoleMock = $this->createPersonSystemRoleMock();

        $obj = $this->createMockService();
        $actual = $obj->assignRole($personMock, $personSystemRoleMock);

        $this->assertInstanceOf('DvsaEntities\Entity\PersonSystemRoleMap', $actual);
        $this->assertEquals(1, $actual->getBusinessRoleStatus()->getId());
        $this->assertEquals(BusinessRoleStatusCode::ACTIVE, $actual->getBusinessRoleStatus()->getCode());
    }

    public function testAssignRole_Exists()
    {
        $this->fakePersonSystemRoleMapRepository_findByPersonAndSystemRole_ReturnObject();
        $personMock = $this->createPersonMock();
        $personSystemRoleMock = $this->createPersonSystemRoleMock();

        $obj = $this->createMockService();
        $this->setExpectedException('Exception', 'PersonSystemRoleMap already exists');
        $actual = $obj->assignRole($personMock, $personSystemRoleMock);
    }

    public function textGetPersonSystemRoleEntityFromName()
    {
        $this->fakePersonSystemRoleRepository_getByName();
        $obj = $this->createMockService();
        $actual = $obj->getPersonSystemRoleEntityFromName(self::FAKE_PERSON_SYSTEM_ROLE);
        $this->assertInstanceOf('PersonSystemRole', $actual);
        $this->assertSame(self::FAKE_PERSON_SYSTEM_ROLE, $actual->getName());
    }

    /**
     * The user being managed currently has roles A and B, but the logged in user
     * does not have permissions to change role D.
     */
    public function testGetPersonManageableInternalRoleCodes()
    {
        $this->fakePersonSystemRoleMapRepository_getPersonActiveInternalRoleCodes();
        $this->fakePermissionToAssignRoleMapRepository_getPermissionCodeByRoleCode();
        $this->fakeAuthService_isGranted();
        $this->fakeRoleRepository_getAllInternalRoles();

        $obj = $this->createMockService();
        $actual = $obj->getPersonManageableInternalRoleCodes(self::PERSON_ID);

        $this->assertSame(['C'], $actual);
    }

    /**
     * The user being managed currently has roles A and B, due to us messing with the data
     * the logged in user now has permission to add role D
     */
    public function testGetPersonManageableInternalRoleCodes_AlteredData()
    {
        // Change the data to allow managing role D
        $this->permissionMap[3]['allowed'] = true;

        $this->fakePersonSystemRoleMapRepository_getPersonActiveInternalRoleCodes();
        $this->fakePermissionToAssignRoleMapRepository_getPermissionCodeByRoleCode();
        $this->fakeAuthService_isGranted();
        $this->fakeRoleRepository_getAllInternalRoles();

        $obj = $this->createMockService();
        $actual = $obj->getPersonManageableInternalRoleCodes(self::PERSON_ID);

        $this->assertSame(['C', 'D'], $actual);
    }

    public function testGetPersonAssignedInternalRoleCodes()
    {
        $this->fakePersonSystemRoleMapRepository_getPersonActiveInternalRoleCodes();

        $obj = $this->createMockService();
        $actual = $obj->getPersonAssignedInternalRoleCodes(self::PERSON_ID);

        $this->assertSame(['A', 'B'], $actual);
    }

    public function testGetPersonAssignedInternalRoleCodes_AlteredData()
    {
        $this->permissionMap[2]['active'] = true;
        $this->fakePersonSystemRoleMapRepository_getPersonActiveInternalRoleCodes();

        $obj = $this->createMockService();
        $actual = $obj->getPersonAssignedInternalRoleCodes(self::PERSON_ID);

        $this->assertSame(['A', 'B', 'C'], $actual);
    }

    public function testGetRoles()
    {
        $this->fakeAuthService_assertGranted();
        $this->fakePersonSystemRoleMapRepository_getPersonActiveInternalRoleCodes();
        $this->fakePermissionToAssignRoleMapRepository_getPermissionCodeByRoleCode();

        $obj = $this->createMockService();
        $actual = $obj->getRoles(self::PERSON_ID);
        $this->assertSame(
            [
                PersonRoleService::ROLES_ASSIGNED => ['A', 'B'],
                PersonRoleService::ROLES_MANAGEABLE => ['C'],
            ],
            $actual
        );
    }

    /**
     * @dataProvider dpResultsPeopleRolesCombination
     */
    public function testPersonHasTradeRole($expectedResult, $personId, $roles)
    {

        $mockEntityManager = XMock::of(EntityManager::class);

        $mockEntityManager->expects($this->any())
            ->method('getRepository')
            ->willReturnCallback(
                function ($entityName) {
                    if ('DvsaEntities\Entity\Role' === $entityName) {
                        return $this->stubRoleRepository();
                    }
                }
            );

        $personRoleService = new PersonRoleService(
            $this->stubRbacRepository($roles),
            $this->getMockObj(EntityRepository::class),
            $this->getMockObj(PermissionToAssignRoleMapRepository::class),
            $this->getMockObj(PersonRepository::class),
            $this->getMockObj(PersonSystemRoleRepository::class),
            $this->getMockObj(PersonSystemRoleMapRepository::class),
            $this->stubRoleRepository(),
            $this->getMockObj(AuthorisationServiceInterface::class),
            $this->getMockObj(RoleEventHelper::class),
            $this->getMockObj(RoleNotificationHelper::class)
        );

        $this->assertEquals(
            $expectedResult,
            $personRoleService->personHasTradeRole($personId)
        );
    }

    public function dpResultsPeopleRolesCombination()
    {
        return [
            [
                false,
                10,
                [
                    'ASSESSMENT',
                    'ASSESSMENT-LINE-MANAGER',
                    'CRON',
                    'DEMOTEST',
                    'GUEST',
                    'SLOT-PURCHASER',
                    'TESTER-APPLICANT-DEMO-TEST-REQUIRED',
                    'TESTER-APPLICANT-INITIAL-TRAINING-FAILED',
                    'TESTER-APPLICANT-INITIAL-TRAINING-REQUIR',
                    'TESTER-INACTIVE',
                    'USER',
                    'GVTS-TESTER',
                    'VM-10519-USER',
                    'DVLA-MANAGER',
                ],
            ],
            [
                true,
                10,
                [
                    'AUTHORISED-EXAMINER',
                    'ASSESSMENT',
                    'ASSESSMENT-LINE-MANAGER',
                    'CRON',
                    'DEMOTEST',
                    'GUEST',
                    'SLOT-PURCHASER',
                    'TESTER-APPLICANT-DEMO-TEST-REQUIRED',
                    'TESTER-APPLICANT-INITIAL-TRAINING-FAILED',
                    'TESTER-APPLICANT-INITIAL-TRAINING-REQUIR',
                    'TESTER-INACTIVE',
                    'USER',
                    'GVTS-TESTER',
                    'VM-10519-USER',
                    'DVLA-MANAGER',
                ],
            ],
            [
                true,
                15,
                [
                    'AUTHORISED-EXAMINER',
                    'AUTHORISED-EXAMINER-DELEGATE',
                    'AUTHORISED-EXAMINER-DESIGNATED-MANAGER',
                    'AUTHORISED-EXAMINER-PRINCIPAL',
                    'SITE-ADMIN',
                    'SITE-MANAGER',
                    'TESTER',
                    'TESTER-ACTIVE',
                ],
            ],
            [
                true,
                20,
                [
                    'AUTHORISED-EXAMINER',
                ],
            ],
            [
                false,
                25,
                [
                    'ASSESSMENT',
                    'ASSESSMENT-LINE-MANAGER',
                    'CRON',
                    'DEMOTEST',
                    'DVSA-AREA-OFFICE-1',
                    'DVSA-SCHEME-MANAGEMENT',
                    'DVSA-SCHEME-USER',
                    'GUEST',
                    'SLOT-PURCHASER',
                    'TESTER-APPLICANT-DEMO-TEST-REQUIRED',
                    'TESTER-APPLICANT-INITIAL-TRAINING-FAILED',
                    'TESTER-APPLICANT-INITIAL-TRAINING-REQUIR',
                    'TESTER-INACTIVE',
                    'USER',
                    'VEHICLE-EXAMINER',
                    'CUSTOMER-SERVICE-MANAGER',
                    'CUSTOMER-SERVICE-CENTRE-OPERATIVE',
                    'FINANCE',
                    'DVLA-OPERATIVE',
                    'DVSA-AREA-OFFICE-2',
                    'GVTS-TESTER',
                    'VM-10519-USER',
                    'DVLA-MANAGER',
                ],
            ],
            [
                false,
                30,
                [
                    'ASSESSMENT',
                ],
            ],
        ];

    }

    /**
     * PRIVATE INTERNAL TEST FUNCTIONS
     */

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function fakeAuthService_assertGranted()
    {
        $mock = $this->getMockObj(AuthorisationServiceInterface::class);
        $mock->expects($this->at(0))
            ->method('assertGranted')
            ->with(PermissionInSystem::MANAGE_DVSA_ROLES)
            ->willReturn(true);

        $this->fakeAuthService_isGranted(1);

        return $mock;
    }

    /**
     * @param int $startNumber
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function fakeAuthService_isGranted($startNumber = 0)
    {
        $mock = $this->getMockObj(AuthorisationServiceInterface::class);
        $count = $startNumber;
        foreach($this->permissionMap as $entry) {
            // Only deal with inactive entries in the map
            if(false === $entry['active']) {
                $mock->expects($this->at($count))
                    ->method('isGranted')
                    ->with($entry['permission'])
                    ->willReturn($entry['allowed']);
                $count++;
            }
        }

        return $mock;
    }

    private function fakeEventHelper_createAssignRoleEvent()
    {
        $mock = $this->getMockObj(RoleEventHelper::class);
        $mock->expects($this->once())
            ->method('createAssignRoleEvent');
        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function fakeNotifcationHelper_sendAssignRoleNotification()
    {
        $mock = $this->getMockObj(RoleNotificationHelper::class);
        $mock->expects($this->once())
            ->method('sendAssignRoleNotification');
        return $mock;
    }

    private function createPersonMock()
    {
        $person = XMock::of(Person::class);
        $person->expects($this->any())
            ->method('getId')
            ->willReturn(self::PERSON_ID);
        return $person;
    }

    private function createPersonSystemRoleMock()
    {
        $personSystemRole = XMock::of(PersonSystemRole::class);
        $personSystemRole->expects($this->any())
            ->method('getId')
            ->willReturn(self::PERSON_SYSTEM_ROLE_ID);
        return $personSystemRole;
    }

    /**
     * @param string $className
     * @param bool $alwaysCreate
     * @return \PHPUnit_Framework_MockObject_MockObject
     * @throws \Exception
     */
    private function getMockObj($className, $alwaysCreate = false)
    {
        if (!isset($this->mocks[$className]) || $alwaysCreate === true) {
            $this->mocks[$className] = XMock::of($className);
        }
        return $this->mocks[$className];
    }

    /**
     * @return Role[]
     * @throws \Exception
     */
    private function createRoleArrayFromPermissionsMap()
    {
        $return = [];
        foreach ($this->permissionMap as $entry) {
            $return[] = (new Role)->setCode($entry['code']);
        }
        return $return;
    }

    /**
     * @return PersonRoleService
     */
    private function createMockService()
    {
        return new PersonRoleService(
            $this->stubRbacRepository(['ROLE-A', 'ROLE-B']),
            $this->getMockObj(EntityRepository::class),
            $this->getMockObj(PermissionToAssignRoleMapRepository::class),
            $this->getMockObj(PersonRepository::class),
            $this->getMockObj(PersonSystemRoleRepository::class),
            $this->getMockObj(PersonSystemRoleMapRepository::class),
            $this->stubRoleRepository(),
            $this->getMockObj(AuthorisationServiceInterface::class),
            $this->getMockObj(RoleEventHelper::class),
            $this->getMockObj(RoleNotificationHelper::class)
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function fakePersonSystemRoleRepository_getByName()
    {
        $mock = $this->getMockObj(PersonSystemRoleRepository::class);
        $mock->expects($this->once())
            ->method('getByName')
            ->with(self::FAKE_PERSON_SYSTEM_ROLE)
            ->willReturn(
                (new PersonSystemRole)
                    ->setName(self::FAKE_PERSON_SYSTEM_ROLE)
                    ->setRole(
                        (new Role())
                            ->setCode(self::FAKE_PERSON_SYSTEM_ROLE)
                    )
            );
        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function fakePersonRepository_find()
    {
        $mock = $this->getMockObj(PersonRepository::class);
        $mock->expects($this->once())
            ->method('find')
            ->with(self::PERSON_ID)
            ->willReturn(
                (new Person())
                    ->setId(self::PERSON_ID)
            );

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function fakePermissionToAssignRoleMapRepository_getPermissionCodeByRoleCode()
    {
        $mock = $this->getMockObj(PermissionToAssignRoleMapRepository::class);

        $counter = 0;
        foreach ($this->permissionMap as $entry) {
            if ($entry['active'] === false) {

                $mock->expects($this->at($counter))
                    ->method('getPermissionCodeByRoleCode')
                    ->with($entry['code'])
                    ->willReturn($entry['permission']);

                $counter++;
            }
        }

        return $mock;
    }

    /**
     * @param string $roleCode
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function fakePermissionToAssignRoleMapRepository_getPermissionCodeByRoleCode_Defined($roleCode)
    {
        $mock = $this->getMockObj(PermissionToAssignRoleMapRepository::class);
        foreach ($this->permissionMap as $entry) {
            if ($entry['code'] === $roleCode) {
                $mock->expects($this->once())
                    ->method('getPermissionCodeByRoleCode')
                    ->with($entry['code'])
                    ->willReturn($entry['permission']);
                return $mock;
            }
        }
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function fakePersonSystemRoleMapRepository_getPersonActiveInternalRoleCodes()
    {
        $mock = $this->getMockObj(PersonSystemRoleMapRepository::class, true);
        $mock->expects($this->any())
            ->method('getPersonActiveInternalRoleCodes')
            ->with(self::PERSON_ID)
            ->willReturn($this->generateActiveRolesFromPermissionMap());
        return $mock;
    }

    /**
     * @return array e.g. [['code' => 'A']]
     */
    private function generateActiveRolesFromPermissionMap()
    {
        $return = [];
        foreach ($this->permissionMap as $entry) {
            if (true === $entry['active']) {
                $return[] = ['code' => $entry['code']];
            }
        }
        return $return;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function fakeRoleRepository_getAllInternalRoles()
    {
        $mock = $this->getMockObj(RoleRepository::class);
        $mock->expects($this->any())
            ->method('getAllInternalRoles')
            ->willReturn($this->createRoleArrayFromPermissionsMap());
        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function fakePersonSystemRoleMapRepository_findByPersonAndSystemRole_Null()
    {
        $mock = $this->getMockObj(PersonSystemRoleMapRepository::class);
        $mock->expects($this->once())
            ->method('findByPersonAndSystemRole')
            ->with(self::PERSON_ID, self::PERSON_SYSTEM_ROLE_ID)
            ->willReturn(null);
        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function fakePersonSystemRoleMapRepository_findByPersonAndSystemRole_ReturnObject()
    {
        $mock = $this->getMockObj(PersonSystemRoleMapRepository::class);
        $mock->expects($this->once())
            ->method('findByPersonAndSystemRole')
            ->with(self::PERSON_ID, self::PERSON_SYSTEM_ROLE_ID)
            ->willReturn(
                (new PersonSystemRoleMap())
                    ->setPerson($this->createPersonMock())
                    ->setPersonSystemRole($this->createPersonSystemRoleMock())
                    ->setBusinessRoleStatus(
                        (new BusinessRoleStatus())
                            ->setId(2)
                            ->setCode(BusinessRoleStatusCode::INACTIVE)
                    )
            );
        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function fakePersonSystemRoleMapRepository_save()
    {
        $mock = $this->getMockObj(PersonSystemRoleMapRepository::class);
        $mock->expects($this->once())
            ->method('save');
        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function fakeBusinessRoleStatusRepository_findOneBy()
    {
        $mock = $this->getMockObj(EntityRepository::class);
        $mock->expects($this->once())
            ->method('findOneBy')
            ->with(['code' => BusinessRoleStatusCode::ACTIVE])
            ->willReturn(
                (new BusinessRoleStatus())
                    ->setId(1)
                    ->setCode(BusinessRoleStatusCode::ACTIVE)
            );
        return $mock;
    }

    private function stubRoleRepository()
    {
        $mockRoleRepository = XMock::of(RoleRepository::class);

        $mockRoleRepository->expects($this->any())
            ->method('getAllTradeRoles')
            ->willReturn(self::dpTradeRoles());

        // Might need to change to something like self::dpTradeRoles() but for internals
        $mockRoleRepository->expects($this->any())
            ->method('getAllInternalRoles')
            ->willReturn($this->createRoleArrayFromPermissionsMap());

        return $mockRoleRepository;
    }

    private function stubRbacRepository($mockPersonRoleCodes)
    {
        $mockRbacRepository = XMock::of(RbacRepository::class);
        $mockRbacRepository->expects($this->any())
            ->method('authorizationDetails')
            ->willReturnCallback(
                function ($personId) use ($mockPersonRoleCodes) {

                    $mockPersonAuthorisation = XMock::of(PersonAuthorization::class);
                    $mockPersonAuthorisation->expects($this->any())
                        ->method('getAllRoles')
                        ->willReturn($mockPersonRoleCodes);

                    return $mockPersonAuthorisation;
                }
            );


        return $mockRbacRepository;
    }

    /**
     * to mock all trade roles coming from the role repository
     */
    private function dpTradeRoles()
    {
        $roles = array_map(
            function ($roleCode) {
                return (new Role())->setCode($roleCode);
            },
            [
                'AUTHORISED-EXAMINER',
                'AUTHORISED-EXAMINER-DELEGATE',
                'AUTHORISED-EXAMINER-DESIGNATED-MANAGER',
                'AUTHORISED-EXAMINER-PRINCIPAL',
                'SITE-ADMIN',
                'SITE-MANAGER',
                'TESTER',
                'TESTER-ACTIVE',
            ]
        );

        return $roles;
    }
}