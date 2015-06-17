<?php

namespace DvsaCommon\Model;

use DvsaCommon\Constants\Role;

class DvsaRole
{
    /**
     * Returns an array of all existing DVSA roles
     *
     * @return array
     */
    public static function getDvsaRoles()
    {
        return [
            Role::DVSA_AREA_OFFICE_1,
            Role::DVSA_AREA_OFFICE_2,
            Role::VEHICLE_EXAMINER,
            Role::DVSA_SCHEME_MANAGEMENT,
            Role::DVSA_SCHEME_USER,
            Role::FINANCE,
            Role::CUSTOMER_SERVICE_CENTRE_OPERATIVE,
            Role::DVLA_OPERATIVE,
        ];
    }

    /**
     * To check if the given role list contain any DVSA role
     *
     * @param array $roles | Array of roles
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
     * To check if the given role is a DVSA role
     *
     * @param string $role
     * @return bool
     */
    public static function isDvsaRole($role)
    {
        return in_array($role, self::getDvsaRoles());
    }
}
