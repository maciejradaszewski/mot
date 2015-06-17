<?php

namespace DvsaCommon\Auth;

class PermissionLevel
{
    const SITE_LEVEL = 'SITE-LEVEL';
    const SYSTEM_LEVEL = 'SYSTEM-LEVEL';
    const ORGANISATION_LEVEL = 'ORGANISATION_LEVEL-LEVEL';

    public function __construct($name, $fileName, $methodName)
    {
        $this->name = $name;
        $this->fileName = $fileName;
        $this->methodName = $methodName;
    }

    /**
     * File that contains list of permissions of this level
     *
     * @param $permissionLevel
     *
     * @return string
     * @throws \Exception
     */
    public static function getFileName($permissionLevel)
    {
        switch ($permissionLevel) {
            case self::SYSTEM_LEVEL:
                return "PermissionInSystem.php";
            case self::ORGANISATION_LEVEL:
                return "PermissionAtOrganisation.php";
            case self::SITE_LEVEL:
                return "PermissionAtSite.php";
        }

        throw new \Exception("Unknown permission level: '" . $permissionLevel . "'");
    }

    /**
     * AuthorisationService methods that are used to assert this permission
     *
     * @param $permissionLevel
     *
     * @return string
     * @throws \Exception
     */
    public static function getMethodSuffix($permissionLevel)
    {
        switch ($permissionLevel) {
            case self::SYSTEM_LEVEL:
                return "()";
            case self::ORGANISATION_LEVEL:
                return "AtOrganisation()";
            case self::SITE_LEVEL:
                return "AtSite()";
        }

        throw new \Exception("Unknown permission level: '" . $permissionLevel . "'");
    }
}
