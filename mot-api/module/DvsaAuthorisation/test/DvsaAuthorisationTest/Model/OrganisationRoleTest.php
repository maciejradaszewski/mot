<?php

namespace DvsaAuthorisationTest\Model;

use DvsaAuthorisation\Model\OrganisationRole;
use PHPUnit_Framework_TestCase;

class OrganisationRoleTest extends PHPUnit_Framework_TestCase
{
    public function testGettersAndSetters()
    {
        $name = 'foobar';
        $organisationId = 1;
        $organisationRole = new OrganisationRole($name);
        $organisationRole->setOrganisationId($organisationId);
        $this->assertEquals($organisationId, $organisationRole->getOrganisationId());
    }
}
