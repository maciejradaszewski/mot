<?php

namespace DvsaCommon\Auth;

use DvsaCommon\Exception\UnauthorisedException;

/**
 * Authorization interface used across tiers.
 */
interface MotAuthorisationServiceInterface
{
    /**
     * @param string $permissionName
     *
     * @return bool
     */
    public function isGranted($permissionName);

    /**
     * @param string $permissionName
     * @param int $siteId
     *
     * @return bool
     */
    public function isGrantedAtSite($permissionName, $siteId);

    /**
     * @param string $permissionName
     *
     * @return bool
     */
    public function isGrantedAtAnySite($permissionName);

    /**
     * @param string $permissionName
     * @param int $orgId
     *
     * @return bool
     */
    public function isGrantedAtOrganisation($permissionName, $orgId);

    /**
     * @param string $permissionName
     *
     * @throws UnauthorisedException
     */
    public function assertGranted($permissionName);

    /**
     * @param string $permissionName
     * @param int $siteId
     *
     * @throws UnauthorisedException
     */
    public function assertGrantedAtSite($permissionName, $siteId);

    public function assertGrantedAtAnySite($permissionName);

    /**
     * @param string $permissionName
     * @param int $orgId
     *
     * @throws UnauthorisedException
     */
    public function assertGrantedAtOrganisation($permissionName, $orgId);

    /**
     * Returns the user's roles (at any level) as an array.
     *
     * @return array
     * @deprecated check permissions, rather than roles.
     */
    public function getRolesAsArray();

    /**
     * Does the logged in user have the specified role?
     *
     * @param $roleName
     *
     * @return boolean
     * @deprecated check permissions, rather than roles
     */
    public function hasRole($roleName);
}
