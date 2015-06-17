<?php

namespace DvsaAuthorisationTest\Model;

use DvsaAuthorisation\Model\Permission;
use PHPUnit_Framework_TestCase;

class PermissionTest extends PHPUnit_Framework_TestCase
{
    public function testGettersAndSetters()
    {
        $name = 'foobar';
        $permission = new Permission();
        $permission->setName($name);
        $this->assertEquals($name, $permission->getName());
        $this->assertEquals($name, $permission->__toString());
    }
}
