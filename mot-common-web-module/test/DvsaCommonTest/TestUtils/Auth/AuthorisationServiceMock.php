<?php

namespace DvsaCommonTest\TestUtils\Auth;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Exception\UnauthorisedException;

/**
 * Helps test multiple assertions in a fluent fashion. The idea of using it in the tests
 * is to set basic set of assertions which can be later modified on a test scenario basis
 */
final class AuthorisationServiceMock implements MotAuthorisationServiceInterface
{
    private $global = [];
    private $siteMap = [];
    private $organisationMap = [];
    private $roles = [];
    private $acceptAll;

    private function __construct($acceptAll)
    {
        $this->acceptAll = $acceptAll;
    }

    /** @return MotAuthorisationServiceInterface */
    public static function grantedAll()
    {
        return new AuthorisationServiceMock(true);
    }

    /** @return AuthorisationServiceMock|MotAuthorisationServiceInterface */
    public static function denyAll()
    {
        return new AuthorisationServiceMock(false);
    }

    public function withRole($role)
    {
        $this->roles[] = $role;
    }

    public function granted($permission)
    {
        $this->global[] = $permission;
        return $this;
    }

    public function grantedAtSite($permission, $siteId)
    {
        $this->siteMap[$siteId][] = $permission;
        return $this;
    }

    public function grantedAtOrganisation($permission, $organisationId)
    {
        $this->organisationMap[$organisationId][] = $permission;
        return $this;
    }


    public function isGranted($permissionName)
    {
        return in_array($permissionName, $this->global) ? true : $this->acceptAll;
    }

    public function isGrantedAtSite($permissionName, $siteId)
    {
        $idxExists = isset($this->siteMap[$siteId]);
        return $idxExists && in_array($permissionName, $this->siteMap[$siteId])
            ? true : $this->acceptAll;
    }

    public function isGrantedAtOrganisation($permissionName, $orgId)
    {
        $idxExists = isset($this->organisationMap[$orgId]);
        return $idxExists && in_array($permissionName, $this->organisationMap[$orgId])
            ? true : $this->acceptAll;
    }

    public function assertGranted($permissionName)
    {
        if (!$this->isGranted($permissionName)) {
            throw new UnauthorisedException("Permission ${permissionName} not given");
        }
    }

    public function assertGrantedAtSite($permissionName, $siteId)
    {
        if (!$this->isGrantedAtSite($permissionName, $siteId)) {
            throw new UnauthorisedException("Permission ${permissionName} not given");
        }
    }

    public function assertGrantedAtOrganisation($permissionName, $orgId)
    {
        if (!$this->isGrantedAtOrganisation($permissionName, $orgId)) {
            throw new UnauthorisedException("Permission ${permissionName} not given");
        }
    }

    public function hasRole($roleName)
    {
        return in_array($roleName, $this->roles);
    }

    /**
     * Returns the user's roles (at any level) as an array.
     *
     * @return array
     * @deprecated check permissions, rather than roles.
     */
    public function getRolesAsArray()
    {
        return $this->roles;
    }
}
