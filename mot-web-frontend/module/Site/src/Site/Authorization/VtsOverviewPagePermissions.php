<?php
namespace Site\Authorization;

use DvsaAuthentication\Model\MotFrontendIdentityInterface;
use DvsaClient\Entity\SitePosition;
use DvsaCommon\Auth\Assertion\UpdateVtsAssertion;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\PermissionAtOrganisation;
use DvsaCommon\Auth\PermissionAtSite;
use DvsaCommon\Auth\PermissionInSystem;
use DvsaCommon\Enum\SiteBusinessRoleCode;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\TypeCheck;
use DvsaClient\Entity\Person;

/**
 * Class VtsOverviewPagePermissions
 *
 * Wraps authorisation service to provide a clean way of verifying what a person can do/see on VTS overview page
 *
 * @package Site\Authorization
 */
class VtsOverviewPagePermissions
{
    /** @var MotAuthorisationServiceInterface */
    private $authorisationService;
    /** @var MotFrontendIdentityInterface */
    private $identity;

    private $vtsId;
    private $vtsData;

    private $positions;

    private $authorisedExaminerId;

    /**
     * @param MotAuthorisationServiceInterface $authorisationService
     * @param MotFrontendIdentityInterface     $identity
     * @param array                            $vtsData
     * @param SitePosition[]                   $positions
     * @param int                              $authorisedExaminerId
     */
    public function __construct(
        MotAuthorisationServiceInterface $authorisationService,
        MotFrontendIdentityInterface $identity,
        array $vtsData,
        $positions,
        $authorisedExaminerId
    ) {
        $this->authorisationService = $authorisationService;
        $this->identity = $identity;
        $this->vtsData = $vtsData;
        $this->vtsId = $vtsData['id'];
        TypeCheck::assertCollectionOfClass($positions, SitePosition::class);
        $this->positions = $positions;
        $this->authorisedExaminerId = $authorisedExaminerId;
    }

    private function isGranted($permission)
    {
        return $this->authorisationService->isGrantedAtSite($permission, $this->vtsId);
    }

    public function canViewTestsInProgress()
    {
        return $this->isGranted(PermissionAtSite::VIEW_TESTS_IN_PROGRESS_AT_VTS);
    }

    public function canViewProfile(Person $person)
    {
        return $this->authorisationService->isGrantedAtSite(
            PermissionAtSite::VTS_EMPLOYEE_PROFILE_READ,
            $this->vtsId
        )
        && $this->personIsEmployee($person);
    }

    private function personIsEmployee(Person $person)
    {
        return ArrayUtils::anyMatch(
            $this->positions,
            function (SitePosition $position) use ($person) {
                return $position->getPerson()->getId() == $person->getId()
                && $position->isActive();
            });
    }

    public function canViewAuthorisedExaminer() {
        return $this->authorisationService->isGrantedAtOrganisation(
            PermissionAtOrganisation::AUTHORISED_EXAMINER_READ,
            $this->authorisedExaminerId
        );
    }

    public function canTestClass1And2()
    {
        $roles = ArrayUtils::tryGet($this->vtsData, 'roles', []);
        return in_array(1, $roles) || in_array(2, $roles);
    }

    public function canTestAnyOfClass3AndAbove()
    {
        $roles = ArrayUtils::tryGet($this->vtsData, 'roles', []);
        $classes = [3, 4, 5, 7];

        return (count(array_intersect($roles, $classes)) > 0);
    }

    public function canChangeDefaultBrakeTests()
    {
        return $this->authorisationService->isGrantedAtSite(
            PermissionAtSite::DEFAULT_BRAKE_TESTS_CHANGE,
            $this->vtsId
        );
    }

    public function canAbortMotTest()
    {
        return $this->authorisationService->isGrantedAtSite(PermissionAtSite::MOT_TEST_ABORT_AT_SITE, $this->vtsId);
    }

    public function canNominateARole()
    {
        return $this->authorisationService->isGrantedAtSite(PermissionAtSite::NOMINATE_ROLE_AT_SITE, $this->vtsId);
    }

    public function canRemoveRoleAtSite()
    {
        return $this->authorisationService->isGrantedAtSite(PermissionAtSite::REMOVE_ROLE_AT_SITE, $this->vtsId);
    }

    public function canUpdateTestingSchedule()
    {
        return $this->authorisationService->isGrantedAtSite(PermissionAtSite::TESTING_SCHEDULE_UPDATE, $this->vtsId);
    }

    public function canViewEventHistory() {
        return $this->authorisationService->isGranted(PermissionInSystem::EVENT_READ);
    }

    public function canRemovePositionAtSite($positionRoleCode)
    {
        // Are we trying to remove a site manager?
        if ($positionRoleCode == SiteBusinessRoleCode::SITE_MANAGER) {
            // Only an AE or AEDM with permission of REMOVE-SITE-MANAGER can do this.
            return $this->authorisationService->isGrantedAtSite(
                PermissionAtSite::REMOVE_SITE_MANAGER,
                $this->vtsId
            );
        }

        return $this->canRemoveRoleAtSite();
    }

    public function canChangeDetails()
    {
        $assertions = new UpdateVtsAssertion($this->authorisationService);

        return $assertions->isGranted($this->vtsId);
    }
}
