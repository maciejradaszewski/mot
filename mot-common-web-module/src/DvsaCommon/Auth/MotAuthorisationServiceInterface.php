<?php

namespace DvsaCommon\Auth;

/**
 * Authorization interface used across tiers.
 */
interface MotAuthorisationServiceInterface
{
    public function isGranted($permissionName);

    public function isGrantedAtSite($permissionName, $siteId);

    public function isGrantedAtAnySite($permissionName);

    public function isGrantedAtOrganisation($permissionName, $orgId);

    public function assertGranted($permissionName);

    public function assertGrantedAtSite($permissionName, $siteId);

    public function assertGrantedAtAnySite($permissionName);

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
