<?php

namespace DvsaCommon\Model;

/**
 ** Internal class for use in RBAC implementation only - should not be used by business code.
 */
class ListOfRolesAndPermissions
{
    /**
     * An array of role names
     *
     * @var array $roles
     */
    private $roles;

    /**
     * An array of permission names
     *
     * @var array $permissions
     */
    private $permissions;

    public function __construct($roles, $permissions)
    {
        $this->permissions = $permissions;
        $this->roles = $roles;
    }

    public static function emptyList()
    {
        return new ListOfRolesAndPermissions([], []);
    }

    public function includesRole($roleName)
    {
        return in_array($roleName, $this->roles);
    }

    public function includesPermission($permissionName)
    {
        return in_array($permissionName, $this->permissions);
    }

    public function asArray()
    {
        return [
            'roles'       => $this->roles,
            'permissions' => $this->permissions,
        ];
    }
}
