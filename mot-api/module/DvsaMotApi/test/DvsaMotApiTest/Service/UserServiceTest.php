<?php

namespace DvsaMotApiTest\Service;

use DvsaAuthorisation\Service\AuthorisationService;
use DvsaCommon\Constants\Role as RoleConstants;
use DvsaCommon\Enum\SiteBusinessRoleCode;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\Person;
use DvsaMotApi\Service\UserService;
use UserFacade\Role;

/**
 * Class UserServiceTest.
 */
class UserServiceTest extends AbstractServiceTestCase
{
    public function testGetAllUserData()
    {
        $username                    = 'tester1';
        $users                       = [new Person()];
        $expectedDataWithoutPassword = $this->getExpectedUserData($username, false);
        $expectedHydratorData        = $this->getExpectedUserData($username);
        $expectedDataArray           = [$expectedDataWithoutPassword];

        $mockRepository = $this->getMockRepository();
        $this->setupMockForSingleCall($mockRepository, 'findAll', $users);

        $mockHydrator = $this->getMockHydrator();
        $this->setupMockForSingleCall($mockHydrator, 'extract', $expectedHydratorData, $users[0]);

        $mockEntityManager = $this->getMockEntityManagerWithRepository($mockRepository, Person::class);

        $mockRoleProvider         = XMock::of(\DvsaAuthorisation\Service\RoleProviderService::class);
        $authorisationServiceMock = XMock::of(AuthorisationService::class);

        $userService = $this->getUserService($mockEntityManager, $mockHydrator, $this->getMockUserFacade(),
            $mockRoleProvider, $authorisationServiceMock);

        $this->assertEquals($expectedDataArray, $userService->getAllUserData());
    }

    public function testGetUserData()
    {
        $username = 'tester1';
        $role = SiteBusinessRoleCode::TESTER;
        $user = new Person();
        $userRole = Role::createRole($role);

        $expectedDataWithoutPassword                   = $this->getExpectedUserData($username, false);
        $expectedDataWithoutPasswordWithRoles          = $this->getExpectedUserData($username, false);
        $expectedDataWithoutPasswordWithRoles['roles'] = [$role];
        $expectedHydratorData                          = $this->getExpectedUserData($username);

        $mockRepository = $this->getMockRepository();
        $this->setupMockForSingleCall($mockRepository, 'findOneBy', $user, ['username' => $username]);

        $mockHydrator = $this->getMockHydrator();
        $this->setupMockForSingleCall($mockHydrator, 'extract', $expectedHydratorData, $user);

        $mockEntityManager = $this->getMockEntityManagerWithRepository($mockRepository, Person::class);
        $mockUserFacade    = $this->getMockUserFacade();

        $mockRoleProvider = XMock::of(\DvsaAuthorisation\Service\RoleProviderService::class);
        $mockRoleProvider
            ->expects($this->once())
            ->method('getRolesForPerson')
            ->will($this->returnValue([$userRole]));

        $authorisationServiceMock = XMock::of(AuthorisationService::class);

        $userService = $this->getUserService($mockEntityManager, $mockHydrator, $mockUserFacade, $mockRoleProvider,
            $authorisationServiceMock);

        $this->assertEquals($expectedDataWithoutPasswordWithRoles, $userService->getUserData($username));
    }

    /**
     * @expectedException        \DvsaCommonApi\Service\Exception\NotFoundException
     * @expectedExceptionMessage Person doesnotexist not found
     */
    public function testGetUserDataThrowsNotFoundExceptionForNullFind()
    {
        $username = 'doesnotexist';

        $mockRepository = $this->getMockRepository();
        $this->setupMockForSingleCall($mockRepository, 'findOneBy', null, ['username' => $username]);

        $mockHydrator = $this->getMockHydrator();

        $mockEntityManager = $this->getMockEntityManagerWithRepository($mockRepository, Person::class);

        $mockRoleProvider = XMock::of(\DvsaAuthorisation\Service\RoleProviderService::class);

        $authorisationServiceMock = XMock::of(AuthorisationService::class);

        $userService = $this->getUserService($mockEntityManager, $mockHydrator, $this->getMockUserFacade(),
            $mockRoleProvider, $authorisationServiceMock);

        $userService->getUserData($username);
    }

    protected function getExpectedUserData($username, $withPassword = true)
    {
        $expectedData = [
            'username'    => $username,
            'password'    => 'Password1',
            'displayName' => 'Tester One',
            'displayRole' => 'Tester',
        ];

        if (!$withPassword) {
            unset($expectedData['password']);
        }

        return $expectedData;
    }

    /**
     * @param $mockEntityManager
     * @param $mockHydrator
     * @param $userFacade
     * @param $roleProvider
     * @param $authorisationService
     *
     * @return \DvsaMotApi\Service\UserService
     */
    private function getUserService($mockEntityManager, $mockHydrator, $userFacade, $roleProvider, $authorisationService)
    {
        $userService = new UserService($mockEntityManager, $mockHydrator, $userFacade, $roleProvider, $authorisationService);

        return $userService;
    }
}
