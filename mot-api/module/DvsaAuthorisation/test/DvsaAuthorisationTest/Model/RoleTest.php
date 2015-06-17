<?php

namespace DvsaAuthorisationTest\Model;

use DvsaAuthorisation\Model\Role;
use PHPUnit_Framework_TestCase;

class RoleTest extends PHPUnit_Framework_TestCase
{
    public function testGettersAndSetters()
    {
        $name = 'foobar';
        $newName = 'barfoo';
        $permissions = ['foo', 'bar'];
        $newPermission = 'becool';
        $role = new Role($name);
        $this->assertEquals($name, $role->getName());
        $role->setName($newName);
        $this->assertEquals($newName, $role->getName());
        $role->setPermissions($permissions);
        $this->assertEquals($permissions, $role->getPermissions());
        $this->assertNotContains($newPermission, $role->getPermissions());
        $role->addPermission($newPermission);
        $this->assertContains($newPermission, $role->getPermissions());
        $this->assertEquals($newName, $role->__toString());
    }
}
