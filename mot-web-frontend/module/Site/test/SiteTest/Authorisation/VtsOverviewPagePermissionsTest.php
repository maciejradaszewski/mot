<?php

namespace SiteTest\Authorisation;

use DvsaAuthentication\Model\MotFrontendIdentityInterface;
use DvsaClient\Entity\Person;
use DvsaClient\Entity\SitePosition;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Enum\BusinessRoleStatusCode;
use DvsaCommonTest\TestUtils\XMock;
use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObj;
use Site\Authorization\VtsOverviewPagePermissions;

class VtsOverviewPagePermissionsTest extends PHPUnit_Framework_TestCase
{
    private static $USER_ID = 9999;
    private static $SITE_ID = 8888;
    private static $ORG_ID = 8881;

    /** @var  MockObj */
    private $mockAuthSrv;
    private $mockIdentity;
    private $mockVtsData;


    public function setUp()
    {
        $this->mockAuthSrv = XMock::of(
            MotAuthorisationServiceInterface::class, ['isGrantedAtSite', 'isGrantedAtOrganisation']
        );

        $this->mockIdentity = XMock::of(MotFrontendIdentityInterface::class, ['getUserId']);
        $this->mockIdentity->expects($this->any())
            ->method('getUserId')
            ->willReturn(self::$USER_ID);

        $this->mockVtsData = [
            'id'           => self::$SITE_ID,
            'organisation' => [
                'id' => self::$ORG_ID,
            ],
        ];
    }

    public function testCanViewTestsInProgress()
    {
        $expectedResult = 'expectedResult';

        $this->mockAuthSrv->expects($this->once())
            ->method('isGrantedAtSite')
            ->with(PermissionAtSite::VIEW_TESTS_IN_PROGRESS_AT_VTS, self::$SITE_ID)
            ->willReturn($expectedResult);

        $obj = new VtsOverviewPagePermissions($this->mockAuthSrv, $this->mockIdentity, $this->mockVtsData, [], 1);

        $actualResult = $obj->canViewTestsInProgress();

        $this->assertEquals($actualResult, $expectedResult);
    }

    public function testCanViewProfileDependsOnAcceptingNomination()
    {
        $this->mockAuthSrv->expects($this->any())
            ->method('isGrantedAtSite')
            ->with(PermissionAtSite::VTS_EMPLOYEE_PROFILE_READ, self::$SITE_ID)
            ->willReturn(true);

        $positions = [];

        // GIVEN I have a person who accepted at least one nomination
        $employee = new Person();
        $employee->setId(1);
        $position = new SitePosition();
        $position->setStatus(BusinessRoleStatusCode::ACTIVE);
        $position->setPerson($employee);

        $positions[] = $position;

        $position = new SitePosition();
        $position->setStatus(BusinessRoleStatusCode::PENDING);
        $position->setPerson($employee);

        $positions[] = $position;

        // AND a person that is nominated but haven't accepted yet.

        $nominee = new Person();
        $nominee->setId(2);
        $position = new SitePosition();
        $position->setStatus(BusinessRoleStatusCode::PENDING);
        $position->setPerson($nominee);

        $positions[] = $position;

        $permissions = new VtsOverviewPagePermissions($this->mockAuthSrv, $this->mockIdentity, $this->mockVtsData, $positions, 1);

        // WHEN I want to view their profiles
        $employeeAccessGranted = $permissions->canViewProfile($employee);

        $nomineeAccessGranted = $permissions->canViewProfile($nominee);

        // THEN I can my employee profile
        $this->assertTrue($employeeAccessGranted);


        // AND I cannot view profile of the nominee
        $this->assertFalse($nomineeAccessGranted);
    }

    public function dataProviderTestCanViewUserProfile()
    {
        return [
            [
                'personId'  => null,
                'isGranted' => true,
                'expect'    => true,
            ],
            [1, false, false],
            [1, true, true],
            [self::$USER_ID, false, true],
        ];
    }

    /**
     * @dataProvider dataProviderTestСanTestClass
     */
    public function testСanTestClass($roles, $expect12, $expect3to7)
    {
        $this->mockVtsData['roles'] = $roles;

        $obj = new VtsOverviewPagePermissions($this->mockAuthSrv, $this->mockIdentity, $this->mockVtsData, [], 1);

        $actualResult12 = $obj->canTestClass1And2();
        $actualResult3to7 = $obj->canTestAnyOfClass3AndAbove();

        $this->assertEquals($actualResult12, $expect12);
        $this->assertEquals($actualResult3to7, $expect3to7);
    }

    public function dataProviderTestСanTestClass()
    {
        return [
            [
                'roles'      => [1, 3],
                'expect12'   => true,
                'expect3to7' => true,
            ],
            [[4, 5], false, true],
            [[1], true, false],
            [[], false, false],
        ];
    }


    public function testCanChangeDefaultBrakeTests()
    {
        $expectResult = 'expect Result';

        $this->mockAuthSrv->expects($this->once())
            ->method('isGrantedAtSite')
            ->with(PermissionAtSite::DEFAULT_BRAKE_TESTS_CHANGE, self::$SITE_ID)
            ->willReturn($expectResult);

        $obj = new VtsOverviewPagePermissions($this->mockAuthSrv, $this->mockIdentity, $this->mockVtsData, [], 1);

        $actualResult = $obj->canChangeDefaultBrakeTests();

        $this->assertEquals($actualResult, $expectResult);
    }

    public function testCanAbortMotTest()
    {
        $expectResult = 'expect Result';

        $this->mockAuthSrv->expects($this->once())
            ->method('isGrantedAtSite')
            ->with(PermissionAtSite::MOT_TEST_ABORT_AT_SITE, self::$SITE_ID)
            ->willReturn($expectResult);

        $obj = new VtsOverviewPagePermissions($this->mockAuthSrv, $this->mockIdentity, $this->mockVtsData, [], 1);

        $actualResult = $obj->canAbortMotTest();

        $this->assertEquals($actualResult, $expectResult);
    }

    public function testCanRemoveARoleAtSite()
    {
        $expectResult = true;

        $this->mockAuthSrv->expects($this->once())
            ->method('isGrantedAtSite')
            ->with(PermissionAtSite::REMOVE_ROLE_AT_SITE, self::$SITE_ID)
            ->willReturn($expectResult);

        $obj = new VtsOverviewPagePermissions($this->mockAuthSrv, $this->mockIdentity, $this->mockVtsData, [], 1);

        $actualResult = $obj->canRemoveRoleAtSite();

        $this->assertEquals($actualResult, $expectResult);
    }
}
