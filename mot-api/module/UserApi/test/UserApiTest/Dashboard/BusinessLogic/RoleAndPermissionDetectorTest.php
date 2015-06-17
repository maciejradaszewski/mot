<?php
namespace UserApiTest\Dashboard\BusinessLogic;

use Doctrine\Common\Annotations\Annotation\Enum;
use DvsaCommon\Constants\Role as RoleConstants;
use DvsaCommon\Enum\AuthorisationForTestingMotStatusCode;
use DvsaCommon\Enum\OrganisationBusinessRoleCode as OrganisationRoleCode;
use DvsaCommon\Enum\SiteBusinessRoleCode;
use DvsaEntities\Entity\AuthorisationForTestingMot;
use DvsaEntities\Entity\AuthorisationForTestingMotStatus;
use DvsaEntities\Entity\VehicleClass;
use UserApi\Dashboard\BusinessLogic\RoleAndPermissionDetector;
use UserApi\Person\Dto\MotTestingAuthorisationCollector;
use UserFacade\Role;

/**
 * Unit tests for RoleAndPermissionDetector
 */
class RoleAndPermissionDetectorTest extends \PHPUnit_Framework_TestCase
{
    public function test_getHero_userRole_shouldReturnUser()
    {
        $roles = [
            Role::createRole(RoleConstants::USER)
        ];

        $detector = new RoleAndPermissionDetector($roles, $this->userWithoutAuthorisations(), true, true);

        $this->assertEquals(RoleAndPermissionDetector::HERO_USER, $detector->getHero());
        $this->assertUserAuthorisedForTestingPermissions($detector);
    }

    public function test_getHero_testerRoleWithoutAuthorisations_shouldReturnUser()
    {
        $roles = [
            Role::createRole(SiteBusinessRoleCode::TESTER)
        ];

        $detector = new RoleAndPermissionDetector($roles, $this->userWithoutAuthorisations(), false, false);

        $this->assertEquals(RoleAndPermissionDetector::HERO_USER, $detector->getHero());
        $this->assertUserNotAuthorisedForTestingPermissions($detector);
    }

    public function test_getHero_testerRoleWithApplicationForAuthorisationApproved_shouldReturnTesterApplicant()
    {
        $roles = [
            Role::createRole(SiteBusinessRoleCode::TESTER)
        ];
        $authorisations = $this->createAuthorisedForVehicleClassesCollector(
            [1 => AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED]
        );

        $detector = new RoleAndPermissionDetector($roles, $authorisations->toArray(), false, false);

        $this->assertEquals(RoleAndPermissionDetector::HERO_TESTER_APPLICANT, $detector->getHero());
        $this->assertUserNotAuthorisedForTestingPermissions($detector);
    }

    public function test_getHero_aedm_shouldReturnAedm()
    {
        $this->runTest_getHero_aeRole_shouldReturnAedm(OrganisationRoleCode::AUTHORISED_EXAMINER_DESIGNATED_MANAGER);
    }

    public function test_getHero_aed_shouldReturnAedm()
    {
        $this->runTest_getHero_aeRole_shouldReturnAedm(OrganisationRoleCode::AUTHORISED_EXAMINER_DELEGATE);
    }

    private function runTest_getHero_aeRole_shouldReturnAedm($role)
    {
        $roles = [
            Role::createRole(RoleConstants::USER),
            Role::createRole($role),
        ];

        $detector = new RoleAndPermissionDetector($roles, $this->userWithoutAuthorisations(), false, false);

        $this->assertEquals(RoleAndPermissionDetector::HERO_AEDM, $detector->getHero());
        $this->assertAedmPermissions($detector, OrganisationRoleCode::AUTHORISED_EXAMINER_DESIGNATED_MANAGER === $role);
    }

    public function test_getHero_testerFullyQualified_shouldReturnTester()
    {
        $detector = $this->runTest_getHero_testerWithAuthorisations_shouldBeTester_returnsDetector(
            $this->fullyQualifiedTester()
        );
        $this->assertFullyAuthorisedTesterPermissions($detector);
    }

    public function test_getHero_testerGroupA_shouldReturnTester()
    {
        $detector = $this->runTest_getHero_testerWithAuthorisations_shouldBeTester_returnsDetector(
            $this->groupATester()
        );
        $this->assertOneGroupTesterPermissions($detector);
    }

    public function test_getHero_testerGroupB_shouldReturnTester()
    {
        $detector = $this->runTest_getHero_testerWithAuthorisations_shouldBeTester_returnsDetector(
            $this->groupBTester()
        );
        $this->assertOneGroupTesterPermissions($detector);
    }

    private function runTest_getHero_testerWithAuthorisations_shouldBeTester_returnsDetector($auths)
    {
        $roles = [
            Role::createRole(SiteBusinessRoleCode::TESTER),
        ];

        $detector = new RoleAndPermissionDetector($roles, $auths, true, true);

        $this->assertEquals(RoleAndPermissionDetector::HERO_TESTER, $detector->getHero());

        return $detector;
    }

    public function test_getHero_dvsaSchemeDifferentUsers_shouldReturnAdmin()
    {
        $this->runTestForDvsaRole(RoleConstants::DVSA_SCHEME_USER);
        $this->runTestForDvsaRole(RoleConstants::DVSA_AREA_OFFICE_1);
        $this->runTestForDvsaRole(RoleConstants::DVSA_SCHEME_MANAGEMENT);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function test_construct_shouldThrownException()
    {
        new RoleAndPermissionDetector(null, null, true, true);
    }

    private function runTestForDvsaRole($role)
    {
        $roles = [
            Role::createRole($role)
        ];

        $detector = new RoleAndPermissionDetector($roles, [], false, false);

        $this->assertEquals(RoleAndPermissionDetector::HERO_DVSA_ADMIN, $detector->getHero());
        $this->assertAdminPermissions($detector);
    }

    private function assertFullyAuthorisedTesterPermissions(RoleAndPermissionDetector $detector)
    {
        $permissions = $detector->getPermissions();
        $this->assertEquals(false, $permissions['tester-application']);
        $this->assertEquals(true, $permissions['vts-list']);
        $this->assertEquals(false, $permissions['ae-application']);
        $this->assertEquals(true, $permissions['aedm-application']);
        $this->assertEquals(true, $permissions['display-applications']);
        $this->assertEquals(false, $permissions['assessment-box']);
        $this->assertEquals(false, $permissions['dvsa-admin-box']);
        $this->assertEquals(true, $permissions['testing-enabled']);
        $this->assertEquals(true, $permissions['tester-contingency-box']);
    }

    private function assertOneGroupTesterPermissions(RoleAndPermissionDetector $detector)
    {
        $permissions = $detector->getPermissions();
        $this->assertEquals(true, $permissions['tester-application']);
        $this->assertEquals(true, $permissions['vts-list']);
        $this->assertEquals(false, $permissions['ae-application']);
        $this->assertEquals(true, $permissions['aedm-application']);
        $this->assertEquals(true, $permissions['display-applications']);
        $this->assertEquals(false, $permissions['assessment-box']);
        $this->assertEquals(false, $permissions['dvsa-admin-box']);
        $this->assertEquals(true, $permissions['testing-enabled']);
    }

    private function assertAedmPermissions(RoleAndPermissionDetector $detector, $isAedm = true)
    {
        $permissions = $detector->getPermissions();
        $this->assertEquals(true, $permissions['tester-application']);
        $this->assertEquals(true, $permissions['vts-list']);
        $this->assertEquals($isAedm, $permissions['ae-application']);
        $this->assertEquals(!$isAedm, $permissions['aedm-application']);
        $this->assertEquals(true, $permissions['display-applications']);
        $this->assertEquals(false, $permissions['assessment-box']);
        $this->assertEquals(false, $permissions['dvsa-admin-box']);
        $this->assertEquals(false, $permissions['testing-enabled']);
    }

    private function assertUserNotAuthorisedForTestingPermissions(RoleAndPermissionDetector $detector)
    {
        $permissions = $detector->getPermissions();
        $this->assertEquals(true, $permissions['tester-application']);
        $this->assertEquals(true, $permissions['vts-list']);
        $this->assertEquals(false, $permissions['ae-application']);
        $this->assertEquals(true, $permissions['aedm-application']);
        $this->assertEquals(true, $permissions['display-applications']);
        $this->assertEquals(false, $permissions['assessment-box']);
        $this->assertEquals(false, $permissions['dvsa-admin-box']);
        $this->assertEquals(false, $permissions['dvsa-admin-box']);
        $this->assertEquals(false, $permissions['testing-enabled']);
    }

    private function assertUserAuthorisedForTestingPermissions(RoleAndPermissionDetector $detector)
    {
        $permissions = $detector->getPermissions();
        $this->assertEquals(true, $permissions['tester-application']);
        $this->assertEquals(true, $permissions['vts-list']);
        $this->assertEquals(false, $permissions['ae-application']);
        $this->assertEquals(true, $permissions['aedm-application']);
        $this->assertEquals(true, $permissions['display-applications']);
        $this->assertEquals(false, $permissions['assessment-box']);
        $this->assertEquals(false, $permissions['dvsa-admin-box']);
        $this->assertEquals(true, $permissions['testing-enabled']);
    }

    private function assertAdminPermissions(RoleAndPermissionDetector $detector)
    {
        $permissions = $detector->getPermissions();
        $this->assertEquals(false, $permissions['tester-application']);
        $this->assertEquals(false, $permissions['vts-list']);
        $this->assertEquals(false, $permissions['ae-application']);
        $this->assertEquals(false, $permissions['aedm-application']);
        $this->assertEquals(false, $permissions['display-applications']);
        $this->assertEquals(true, $permissions['assessment-box']);
        $this->assertEquals(true, $permissions['dvsa-admin-box']);
        $this->assertEquals(false, $permissions['testing-enabled']);
    }

    private function createAuthorisedForVehicleClassesCollector($setup)
    {
        $authorisations = [];

        foreach ($setup as $class => $statusCode) {
            $vehicleClass = new VehicleClass();
            $vehicleClass->setCode($class);
            $status = new AuthorisationForTestingMotStatus();
            $status->setCode($statusCode);
            $authorisation = new AuthorisationForTestingMot();
            $authorisation->setVehicleClass($vehicleClass);
            $authorisation->setStatus($status);

            $authorisations[] = $authorisation;
        }
        return new MotTestingAuthorisationCollector($authorisations);
    }

    private function userWithoutAuthorisations()
    {
        return $this->createAuthorisedForVehicleClassesCollector(
            [
                1 => null,
                2 => null,
                3 => null,
                4 => null,
                5 => null,
                7 => null,
            ]
        )->toArray();
    }

    private function fullyQualifiedTester()
    {
        return $this->createAuthorisedForVehicleClassesCollector(
            [
                1 => AuthorisationForTestingMotStatusCode::QUALIFIED,
                2 => AuthorisationForTestingMotStatusCode::QUALIFIED,
                3 => AuthorisationForTestingMotStatusCode::QUALIFIED,
                4 => AuthorisationForTestingMotStatusCode::QUALIFIED,
                5 => AuthorisationForTestingMotStatusCode::QUALIFIED,
                7 => AuthorisationForTestingMotStatusCode::QUALIFIED,
            ]
        )->toArray();
    }

    private function groupATester()
    {
        return $this->createAuthorisedForVehicleClassesCollector(
            [
                1 => AuthorisationForTestingMotStatusCode::QUALIFIED,
                2 => AuthorisationForTestingMotStatusCode::QUALIFIED,
                3 => null,
                4 => null,
                5 => null,
                7 => null,
            ]
        )->toArray();
    }

    private function groupBTester()
    {
        return $this->createAuthorisedForVehicleClassesCollector(
            [
                1 => null,
                2 => null,
                3 => AuthorisationForTestingMotStatusCode::QUALIFIED,
                4 => AuthorisationForTestingMotStatusCode::QUALIFIED,
                5 => AuthorisationForTestingMotStatusCode::QUALIFIED,
                7 => AuthorisationForTestingMotStatusCode::QUALIFIED,
            ]
        )->toArray();
    }
}
