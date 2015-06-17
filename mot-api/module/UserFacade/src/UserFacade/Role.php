<?php

namespace UserFacade;

use DvsaCommon\Constants\Role as RoleConstants;
use DvsaCommon\Enum\OrganisationBusinessRoleCode;
use DvsaCommon\Enum\SiteBusinessRoleCode;

/**
 * Class Role
 */
class Role
{
    /**
     * @var string
     * @see \DvsaCommon\Constants\Role
     */
    private $role;

    private static $roles
        = [
            RoleConstants::ASSESSMENT,
            RoleConstants::ASSESSMENT_LINE_MANAGER,
            RoleConstants::AUTHORISED_EXAMINER,
            OrganisationBusinessRoleCode::AUTHORISED_EXAMINER_DESIGNATED_MANAGER,
            OrganisationBusinessRoleCode::AUTHORISED_EXAMINER_DELEGATE,
            RoleConstants::CRON,
            RoleConstants::DEMOTEST,
            RoleConstants::DVSA_AREA_OFFICE_1,
            RoleConstants::DVSA_SCHEME_MANAGEMENT,
            RoleConstants::DVSA_SCHEME_USER,
            RoleConstants::GUEST,
            RoleConstants::SLOT_PURCHASER,
            SiteBusinessRoleCode::TESTER,
            RoleConstants::TESTER_ACTIVE,
            RoleConstants::TESTER_APPLICANT_INITIAL_TRAINING_REQUIRED,
            RoleConstants::TESTER_APPLICANT_INITIAL_TRAINING_FAILED,
            RoleConstants::TESTER_APPLICANT_DEMO_TEST_REQUIRED,
            RoleConstants::TESTER_INACTIVE,
            RoleConstants::USER,
            RoleConstants::VEHICLE_EXAMINER,
            SiteBusinessRoleCode::SITE_MANAGER,
            SiteBusinessRoleCode::SITE_ADMIN,
            RoleConstants::CUSTOMER_SERVICE_CENTRE_OPERATIVE,
            RoleConstants::DVLA_OPERATIVE,
            RoleConstants::FINANCE
        ];

    /**
     * @param string $role see \DvsaCommon\Constants\Role
     * @return Role
     */
    public static function createRole($role)
    {
        if (in_array($role, self::$roles)) {
            return new Role($role);
        }

        throw new \InvalidArgumentException('Invalid role "' . $role . '"');
    }

    private function __construct($role)
    {
        $this->role = $role;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function __toString()
    {
        return $this->role;
    }
}
