<?php

namespace UserApi\Dashboard\BusinessLogic;

use DvsaCommon\Constants\Role;
use DvsaCommon\Enum\AuthorisationForTestingMotStatusCode;
use DvsaCommon\Enum\OrganisationBusinessRoleCode;
use DvsaCommon\Enum\SiteBusinessRoleCode;
use DvsaCommon\Utility\TypeCheck;

/**
 * Logic for roles and permissions for displaying data on dashboard
 */
class RoleAndPermissionDetector
{
    const HERO_USER = 'user';
    const HERO_VE = 'vehicle-examiner';
    const HERO_AEDM = 'aedm';
    const HERO_TESTER_APPLICANT = 'testerApplicant';
    const HERO_TESTER = 'tester';
    const HERO_DVSA_ADMIN = 'admin';
    const HERO_FINANCE = 'finance';

    private $isVe = false;
    private $isAedm = false;
    private $hasAedmRole = false;
    private $hasTesterRole = false;
    private $isDvsaAdmin = false;
    private $isTesterApplicant = false;
    private $isTester = false;
    private $isFinance = false;
    private $authorisations = [];
    private $testingEnabled = false;

    /**
     * TODO: this method should use RBAC and authentication service, when it's done and ready to use
     *
     * @param Role[] $roles
     * @param array  $vtcAuthorisations
     * @param boolean $isTesterQualified
     * @param boolean $isTesterActive
     *
     * @throws \InvalidArgumentException
     */
    public function __construct($roles, $vtcAuthorisations, $isTesterQualified, $isTesterActive)
    {
        TypeCheck::assertArray($roles);
        TypeCheck::assertArray($vtcAuthorisations);
        $this->validateBooleanType($isTesterQualified);
        $this->validateBooleanType($isTesterActive);

        $this->authorisations = $vtcAuthorisations;
        $this->testingEnabled = $isTesterQualified && $isTesterActive;

        /** @var $role Role */
        foreach ($roles as $role) {
            switch ($role) {
                case Role::DVSA_AREA_OFFICE_1:
                case Role::DVSA_SCHEME_MANAGEMENT:
                case Role::DVSA_SCHEME_USER:
                    $this->isDvsaAdmin = true;
                    break;
                case SiteBusinessRoleCode::TESTER:
                    $this->hasTesterRole = true;
                    break;
                case Role::VEHICLE_EXAMINER:
                    $this->isVe = true;
                    break;
                case OrganisationBusinessRoleCode::AUTHORISED_EXAMINER_DESIGNATED_MANAGER:
                    $this->hasAedmRole = true;
                    // I want to know if user HAS aedm role, but he is also "AEDM hero" for other roles
                case OrganisationBusinessRoleCode::AUTHORISED_EXAMINER_DELEGATE:
                case SiteBusinessRoleCode::SITE_MANAGER:
                case SiteBusinessRoleCode::SITE_ADMIN:
                    $this->isAedm = true;
                    break;
                case Role::FINANCE:
                    $this->isFinance = true;
                    break;
            }
        }
    }

    /**
     * @return mixed
     */
    public function getHero()
    {
        if (true === $this->isDvsaAdmin) {
            return self::HERO_DVSA_ADMIN;
        }

        if (true === $this->hasTesterRole) {
            $this->isTesterApplicant = $this->isAnyAuthorisationInStatus(
                [
                    AuthorisationForTestingMotStatusCode::INITIAL_TRAINING_NEEDED,
                    AuthorisationForTestingMotStatusCode::DEMO_TEST_NEEDED,
                ]
            );

            $this->isTester = $this->isAnyAuthorisationInStatus(
                [
                    AuthorisationForTestingMotStatusCode::QUALIFIED,
                    AuthorisationForTestingMotStatusCode::SUSPENDED,
                    AuthorisationForTestingMotStatusCode::REFRESHER_NEEDED,
                ]
            );
        }

        if (true === $this->isTester) {
            return self::HERO_TESTER;
        }

        if (true === $this->isTesterApplicant) {
            return self::HERO_TESTER_APPLICANT;
        }

        if (true === $this->isVe) {
            return self::HERO_VE;
        }

        if (true === $this->isFinance) {
            return self::HERO_FINANCE;
        }

        return $this->isAedm ? self::HERO_AEDM : self::HERO_USER;
    }

    /**
     * @param array $statuses
     *
     * @return bool
     */
    private function isAnyAuthorisationInStatus($statuses)
    {
        foreach ($this->authorisations as $vtcAuth => $status) {
            if (in_array($status, $statuses)) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return array
     */
    public function getPermissions()
    {
        $permissions = [
            'vts-list'             => false,
            'tester-application'   => false,
            'ae-application'       => false,
            'vts-application'      => false,
            'aedm-application'     => false,
            'dvsa-admin-box'       => false,
            'assessment-box'       => false,
            'display-applications' => false,
            'testing-enabled'      => false,
            'tester-stats-box'     => false,
            'tester-contingency-box' => false,
        ];

        if ($this->isDvsaAdmin) {
            $permissions['assessment-box'] = $permissions['dvsa-admin-box'] = true;
        } else {
            $permissions['vts-list'] = $permissions['vts-application'] = true;
            $permissions['aedm-application'] = !$this->hasAedmRole;
            $permissions['ae-application'] = $this->hasAedmRole;
            $permissions['tester-application'] = $this->canApplyForAnyClass();

            $permissions['display-applications'] = (
                $permissions['tester-application']
                || $permissions['aedm-application']
                || $permissions['ae-application']
                || $permissions['vts-application']
            );

            $permissions['tester-stats-box'] = $this->isTester;
            $permissions['tester-contingency-box'] = $this->isTester;
            $permissions['testing-enabled'] = $this->testingEnabled;
        }

        return $permissions;
    }

    /**
     * @return bool
     */
    private function canApplyForAnyClass()
    {
        if (false === $this->isDvsaAdmin) {
            foreach ($this->authorisations as $vtcAuth => $status) {
                if (null === $status) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param boolean $input
     *
     * @throws \InvalidArgumentException
     */
    private function validateBooleanType($input)
    {
        if (false === is_bool($input)) {
            throw new \InvalidArgumentException();
        }
    }
}
