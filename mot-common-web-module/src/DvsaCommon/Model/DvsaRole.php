<?php

namespace DvsaCommon\Model;

use DvsaCommon\Enum\RoleCode;

class DvsaRole
{
    /**
     * Returns an array of all existing DVSA roles.
     *
     * @return array
     */
    public static function getDvsaRoles()
    {
        return [
            RoleCode::AREA_OFFICE_1,
            RoleCode::AREA_OFFICE_2,
            RoleCode::VEHICLE_EXAMINER,
            RoleCode::SCHEME_MANAGER,
            RoleCode::SCHEME_USER,
            RoleCode::FINANCE,
            RoleCode::CUSTOMER_SERVICE_OPERATIVE,
            RoleCode::DVLA_OPERATIVE,
            RoleCode::DVLA_MANAGER,
            RoleCode::CUSTOMER_SERVICE_MANAGER,
        ];
    }

    /**
     * Returns an array of all DVSA roles that can receive special notices.
     *
     * @return array
     */
    public static function getSpecialNoticeRecipientsRoles()
    {
        return [
            RoleCode::AREA_OFFICE_1,
            RoleCode::AREA_OFFICE_2,
            RoleCode::VEHICLE_EXAMINER,
            RoleCode::SCHEME_MANAGER,
            RoleCode::SCHEME_USER,
            RoleCode::CUSTOMER_SERVICE_OPERATIVE,
            RoleCode::CUSTOMER_SERVICE_MANAGER,
        ];
    }

    /**
     * To check if the given role list contain any DVSA role.
     *
     * @param array $roles | Array of roles
     *
     * @return bool
     */
    public static function containDvsaRole($roles)
    {
        $hasDvsaRole = false;

        foreach ($roles as $role) {
            if (self::isDvsaRole($role)) {
                $hasDvsaRole = true;
                break;
            }
        }

        return $hasDvsaRole;
    }

    /**
     * To check if the given role is a DVSA role.
     *
     * @param string $role
     *
     * @return bool
     */
    public static function isDvsaRole($role)
    {
        return in_array($role, self::getDvsaRoles());
    }
}
