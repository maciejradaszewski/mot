<?php

namespace DvsaCommonTest\TestUtils\Auth;

use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Exception\UnauthorisedException;
use DvsaCommon\Utility\ArrayUtils;
use Symfony\Component\Finder\Exception\OperationNotPermitedException;

/**
 * Helps test multiple assertions in a fluent fashion. The idea of using it in the tests
 * is to set basic set of assertions which can be later modified on a test scenario basis
 */
final class GrantAllAuthorisationServiceStub implements MotAuthorisationServiceInterface
{
    private $roles = [];

    public function isGranted($permissionName)
    {
        return true;
    }

    public function isGrantedAtSite($permissionName, $siteId)
    {
        return true;
    }

    public function isGrantedAtAnySite($permissionName)
    {
        return true;
    }

    public function isGrantedAtOrganisation($permissionName, $orgId)
    {
        return true;
    }

    public function assertGranted($permissionName)
    {

    }

    public function assertGrantedAtSite($permissionName, $siteId)
    {

    }

    public function assertGrantedAtAnySite($permissionName)
    {

    }

    public function assertGrantedAtOrganisation($permissionName, $orgId)
    {

    }

    public function withRole($role)
    {
        $this->roles[] = $role;
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
