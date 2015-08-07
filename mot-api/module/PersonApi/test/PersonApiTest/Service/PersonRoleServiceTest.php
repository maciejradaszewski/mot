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
use DvsaCommon\Exception\UnauthorisedException;

class PersonRoleServiceTest extends AbstractServiceTestCase
{
    const PERSON_ID = 1;
    const PERSON_SYSTEM_ROLE_ID = 42;
    const FAKE_PERSON_SYSTEM_ROLE = 'fakeRole';
    const FAKE_PERMISSION = 'iAmAFaker';

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

    /**
     * Test creating a role for a user
     */
    public function testCreate()
    {
        $obj = XMock::of(
            PersonRoleService::class,
            [
                "assertManageRolePermission",
                "checkPersonHasTradeRole",
                "getPersonEntity",
                "getPersonSystemRoleEntityFromName",
                "getPermissionCodeFromPersonSystemRole",
                "assertSystemRolePermission",
                "addRole",
                "sendAssignRoleEvent",
                "sendAssignRoleNotification",
            ]
        );
        $person = $this->createPersonMock();
        $personSystemRole = $this->createPersonSystemRoleMock();

        $obj->expects($this->once())->method("assertManageRolePermission");
        $obj->expects($this->once())->method("checkPersonHasTradeRole")->with(self::PERSON_ID);
        $obj->expects($this->once())->method("getPersonEntity")->with(self::PERSON_ID)->willReturn($person);
        $obj->expects($this->once())->method("assertSystemRolePermission")->with(self::FAKE_PERMISSION);
        $obj->expects($this->once())->method("addRole")->with($person, $personSystemRole);
        $obj->expects($this->once())->method("sendAssignRoleEvent")->with($person, $personSystemRole);
        $obj->expects($this->once())->method("sendAssignRoleNotification")->with($person, $personSystemRole);

        $obj->expects($this->once())
            ->method("getPersonSystemRoleEntityFromName")
            ->with(self::FAKE_PERSON_SYSTEM_ROLE)
            ->willReturn($personSystemRole);

        $obj->expects($this->once())
            ->method("getPermissionCodeFromPersonSystemRole")
            ->with($personSystemRole)
            ->willReturn(self::FAKE_PERMISSION);

        $obj->create(self::PERSON_ID, ['personSystemRoleCode' => self::FAKE_PERSON_SYSTEM_ROLE]);
    }

    /**
     * Test that the delete function calls the correct methods with the corect values.
     * All methods are individually tested and we only confirm that delete calls them.
     * There is no need to test functions multiple times.
     */
    public function testDelete()
    {
        $obj = XMock::of(
            PersonRoleService::class,
            [
                "assertManageRolePermission",
                "checkPersonHasTradeRole",
                "getPersonEntity",
                "getPersonSystemRoleEntityFromName",
                "getPermissionCodeFromPersonSystemRole",
                "assertSystemRolePermission",
                "removeRole",
                "sendRemoveRoleEvent",
                "sendRemoveRoleNotification",
            ]
        );
        $person = $this->createPersonMock();
        $personSystemRole = $this->createPersonSystemRoleMock();

        $obj->expects($this->once())->method("assertManageRolePermission");
        $obj->expects($this->once())->method("checkPersonHasTradeRole")->with(self::PERSON_ID);
        $obj->expects($this->once())->method("getPersonEntity")->with(self::PERSON_ID)->willReturn($person);
        $obj->expects($this->once())->method("assertSystemRolePermission")->with(self::FAKE_PERMISSION);
        $obj->expects($this->once())->method("sendRemoveRoleEvent")->with($person, $personSystemRole);
        $obj->expects($this->once())->method("sendRemoveRoleNotification")->with($person, $personSystemRole);
        $obj->expects($this->once())->method("removeRole")->with($person, $personSystemRole);

        $obj->expects($this->once())
            ->method("getPersonSystemRoleEntityFromName")
            ->with(self::FAKE_PERSON_SYSTEM_ROLE)
            ->willReturn($personSystemRole);

        $obj->expects($this->once())
            ->method("getPermissionCodeFromPersonSystemRole")
            ->with($personSystemRole)
            ->willReturn(self::FAKE_PERMISSION);

        $obj->delete(self::PERSON_ID, self::FAKE_PERSON_SYSTEM_ROLE);
    }

    /**
     * Test the Get person system role entity function
     */
    public function testGetPersonSystemRoleEntityFromName()
    {
        $this->fakePersonSystemRoleRepository_getByName();
        $obj = $this->createServiceWithMocks();
        $obj->getPersonSystemRoleEntityFromName(self::FAKE_PERSON_SYSTEM_ROLE);
    }

    /**
     * test a person who has permission to manage roles
     */
    public function testAssertManageRolePermission()
    {
        $this->fakeAuthService_assertGranted(PermissionInSystem::MANAGE_DVSA_ROLES);
        $obj = $this->createServiceWithMocks();
        $obj->assertManageRolePermission();
    }

    /**
     * Test for a person who does not have a trade role
     */
    public function testCheckPersonHasTradeRole_PersonHasRole()
    {
        $obj = $this->createPersonRoleServiceMock(false);

        $obj->checkPersonHasTradeRole(self::PERSON_ID);
    }

    /**
     * Test for a person who does not have a trade role
     * @expectedException \Exception
     * @expectedExceptionMessage Its not possible to assign an "internal" role to a "trade" role owner
     */
    public function testCheckPersonHasTradeRole_DoesNotHaveRole()
    {
        $obj = $this->createPersonRoleServiceMock(true);

        $obj->checkPersonHasTradeRole(self::PERSON_ID);
    }

    /**
     * Ensure the auth service calls the assertGranted function.
     */
    public function testAssertSystemRolePermission()
    {
        $this->fakeAuthService_assertGranted(self::FAKE_PERMISSION);
        $obj = $this->createServiceWithMocks();
        $obj->assertSystemRolePermission(self::FAKE_PERMISSION);
    }

    /**
     * Test the getPersonSystemRoleMap returns a PersonSystemRoleMap for a Person and PersonSystemRole
     */
    public function testGetPersonSystemRoleMap()
    {
        $this->fakePersonSystemRoleMapRepository_findByPersonAndSystemRole_ReturnObject();
        $person = $this->createPersonMock();
        $personSystemRole = $this->createPersonSystemRoleMock();

        $obj = $this->createServiceWithMocks();
        $actual = $obj->getPersonSystemRoleMap($person, $personSystemRole);
        $this->assertInstanceOf('DvsaEntities\Entity\PersonSystemRoleMap', $actual);
    }

    /**
     * Ensure that we trigger the event in the Role Event service
     */
    public function testSendAssignRoleEvent()
    {
        $this->fakeEventHelper_createAssignRoleEvent();
        $obj = $this->createServiceWithMocks();
        $obj->sendAssignRoleEvent(
            $this->createPersonMock(),
            $this->createPersonSystemRoleMock()
        );
    }
    /**
     * Ensure that we trigger the event in the Role Event service
     */
    public function testSendRemoveRoleEvent()
    {
        $this->fakeEventHelper_createRemoveRoleEvent();
        $obj = $this->createServiceWithMocks();
        $obj->sendRemoveRoleEvent(
            $this->createPersonMock(),
            $this->createPersonSystemRoleMock()
        );
    }

    /**
     * Ensure that we trigger the event in the Role Notification service
     */
    public function testSendAssignRoleNotification()
    {
        $this->fakeNotificationHelper_sendAssignRoleNotification();
        $obj = $this->createServiceWithMocks();
        $obj->sendAssignRoleNotification(
            $this->createPersonMock(),
            $this->createPersonSystemRoleMock()
        );
    }

    /**
     * Ensure that we trigger the event in the Role Notification service
     */
    public function testSendRemoveRoleNotification()
    {
        $this->fakeNotificationHelper_sendRemoveRoleNotification();
        $obj = $this->createServiceWithMocks();
        $obj->sendRemoveRoleNotification(
            $this->createPersonMock(),
            $this->createPersonSystemRoleMock()
        );
    }

    /**
     * Test assigning a role when it does not exist
     * @throws \Exception
     */
    public function testAssignRole_NotExists()
    {
        $this->fakePersonSystemRoleMapRepository_findByPersonAndSystemRole_Null();
        $this->fakeBusinessRoleStatusRepository_findOneBy();
        $this->fakePersonSystemRoleMapRepository_save();
        $personMock = $this->createPersonMock();
        $personSystemRoleMock = $this->createPersonSystemRoleMock();

        $obj = $this->createServiceWithMocks();
        $actual = $obj->addRole($personMock, $personSystemRoleMock);

        $this->assertInstanceOf('DvsaEntities\Entity\PersonSystemRoleMap', $actual);
        $this->assertEquals(1, $actual->getBusinessRoleStatus()->getId());
        $this->assertEquals(BusinessRoleStatusCode::ACTIVE, $actual->getBusinessRoleStatus()->getCode());
    }

    /**
     * Test assigning a role when it already exists
     * @throws \Exception
     */
    public function testAssignRole_Exists()
    {
        $this->fakePersonSystemRoleMapRepository_findByPersonAndSystemRole_ReturnObject();
        $personMock = $this->createPersonMock();
        $personSystemRoleMock = $this->createPersonSystemRoleMock();

        $obj = $this->createServiceWithMocks();
        $this->setExpectedException('Exception', 'PersonSystemRoleMap already exists');
        $actual = $obj->addRole($personMock, $personSystemRoleMock);
    }

    /**
     * Test removing a role for a role that exists
     */
    public function testRemoveRole_Exists()
    {
        $personSystemRoleMock = $this->fakePersonSystemRoleMapRepository_findByPersonAndSystemRole_ReturnObject();
        $personSystemRoleMock->expects($this->once())->method('remove');

        $personMock = $this->createPersonMock();
        $personSystemRoleMock = $this->createPersonSystemRoleMock();

        $obj = $this->createServiceWithMocks();
        $obj->removeRole($personMock, $personSystemRoleMock);
    }

    /**
     * test removing a role when the role does not exist
     * @expectedException \Exception
     * @expectedExceptionMessage PersonSystemRoleMap does not exist
     */
    public function testRemoveRole_DoesNotExist()
    {
        $this->fakePersonSystemRoleMapRepository_findByPersonAndSystemRole_Null();

        $personMock = $this->createPersonMock();
        $personSystemRoleMock = $this->createPersonSystemRoleMock();

        $obj = $this->createServiceWithMocks();
        $obj->removeRole($personMock, $personSystemRoleMock);
    }

    /**
     * Test getting a system role entity by name
     */
    public function textGetPersonSystemRoleEntityFromName()
    {
        $this->fakePersonSystemRoleRepository_getByName();
        $obj = $this->createServiceWithMocks();
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

        $obj = $this->createServiceWithMocks();
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

        $obj = $this->createServiceWithMocks();
        $actual = $obj->getPersonManageableInternalRoleCodes(self::PERSON_ID);

        $this->assertSame(['C', 'D'], $actual);
    }

    /**
     * Test getting a person's assigned role codes
     */
    public function testGetPersonAssignedInternalRoleCodes()
    {
        $this->fakePersonSystemRoleMapRepository_getPersonActiveInternalRoleCodes();

        $obj = $this->createServiceWithMocks();
        $actual = $obj->getPersonAssignedInternalRoleCodes(self::PERSON_ID);

        $this->assertSame(['A', 'B'], $actual);
    }

    /**
     * test getting a persons internal role codes after modification
     */
    public function testGetPersonAssignedInternalRoleCodes_AlteredData()
    {
        $this->permissionMap[2]['active'] = true;
        $this->fakePersonSystemRoleMapRepository_getPersonActiveInternalRoleCodes();

        $obj = $this->createServiceWithMocks();
        $actual = $obj->getPersonAssignedInternalRoleCodes(self::PERSON_ID);

        $this->assertSame(['A', 'B', 'C'], $actual);
    }

    /**
     * Test getting the roles for a user
     */
    public function testGetRoles()
    {
        $this->fakeAuthService_assertGranted(PermissionInSystem::MANAGE_DVSA_ROLES);
        $this->fakeAuthService_isGranted(1);
        $this->fakePersonSystemRoleMapRepository_getPersonActiveInternalRoleCodes();
        $this->fakePermissionToAssignRoleMapRepository_getPermissionCodeByRoleCode();

        $obj = $this->createServiceWithMocks();
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
     * Test a person has trade role
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

    /**
     * @return array
     */
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
     * @param string $with
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function fakeAuthService_assertGranted($with)
    {
        $mock = $this->getMockObj(AuthorisationServiceInterface::class);
        $mock->expects($this->at(0))
            ->method('assertGranted')
            ->with($with)
            ->willReturn(true);

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

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
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
    private function fakeEventHelper_createRemoveRoleEvent()
    {
        $mock = $this->getMockObj(RoleEventHelper::class);
        $mock->expects($this->once())
            ->method('createRemoveRoleEvent');
        return $mock;
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function fakeNotificationHelper_sendAssignRoleNotification()
    {
        $mock = $this->getMockObj(RoleNotificationHelper::class);
        $mock->expects($this->once())
            ->method('sendAssignRoleNotification');
        return $mock;
    }


    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function fakeNotificationHelper_sendRemoveRoleNotification()
    {
        $mock = $this->getMockObj(RoleNotificationHelper::class);
        $mock->expects($this->once())
            ->method('sendRemoveRoleNotification');
        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     * @throws \Exception
     */
    private function createPersonMock()
    {
        $person = XMock::of(Person::class);
        $person->expects($this->any())
            ->method('getId')
            ->willReturn(self::PERSON_ID);
        return $person;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     * @throws \Exception
     */
    private function createPersonSystemRoleMock()
    {
        $personSystemRole = XMock::of(PersonSystemRole::class);
        $personSystemRole->expects($this->any())
            ->method('getId')
            ->willReturn(self::PERSON_SYSTEM_ROLE_ID);
        return $personSystemRole;
    }

    /**
     * @param bool|false $willReturn
     * @return \PHPUnit_Framework_MockObject_MockObject
     * @throws \Exception
     */
    private function createPersonRoleServiceMock($willReturn = false)
    {
        $mock = XMock::of(PersonRoleService::class, ["personHasTradeRole"]);
        $mock->expects($this->once())
            ->method("personHasTradeRole")
            ->with(self::PERSON_ID)
            ->willReturn($willReturn);
        return $mock;
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
    private function createServiceWithMocks()
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
                    ->setId(self::PERSON_SYSTEM_ROLE_ID)
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
    private function fakePersonSystemRoleMapRepository_findByPersonAndSystemRole_PersonSystemRoleMap()
    {
        $personSystemRoleMap =  $this->getMockObj(PersonSystemRoleMap::class);
        $personSystemRoleMap->expects($this->once())
            ->method('getPersonID')
            ->willReturn(self::PERSON_ID);

        $personSystemRoleMap->expects($this->once())
            ->method('getPersonSystemRoleID')
            ->willReturn(self::PERSON_SYSTEM_ROLE_ID);


        $mock = $this->getMockObj(PersonSystemRoleMapRepository::class);

        $mock->expects($this->once())
            ->method('findByPersonAndSystemRole')
            ->with(self::PERSON_ID, self::PERSON_SYSTEM_ROLE_ID)
            ->willReturn($personSystemRoleMap);

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

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     * @throws \Exception
     */
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

    /**
     * @param $mockPersonRoleCodes
     * @return \PHPUnit_Framework_MockObject_MockObject
     * @throws \Exception
     */
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
     * @return array
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