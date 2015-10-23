<?php

namespace DvsaCommonTest\TestUtils\Auth;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommon\Utility\ArrayUtils;

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

    public function withRole($role)
    {
        $this->roles[] = $role;
    }

    public function setAllowAll()
    {
        return $this;
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
        return in_array($permissionName, $this->global);
    }

    public function isGrantedAtSite($permissionName, $siteId)
    {
        $idxExists = isset($this->siteMap[$siteId]);
        return $idxExists && in_array($permissionName, $this->siteMap[$siteId]);
    }

    public function isGrantedAtAnySite($permissionName)
    {
        $sites = ArrayUtils::getKeys($this->siteMap);
        foreach ($sites as $id) {
            if ($this->isGrantedAtSite($permissionName, $id)) {
                return true;
            }
        }

        return false;
    }

    public function isGrantedAtOrganisation($permissionName, $orgId)
    {
        $idxExists = isset($this->organisationMap[$orgId]);
        return $idxExists && in_array($permissionName, $this->organisationMap[$orgId]);
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

    public function assertGrantedAtAnySite($permissionName)
    {
        if (!$this->isGrantedAtAnySite($permissionName)) {
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

    public function getHero($personId = null)
    {
        return 'tester';
    }
}
