<?php

namespace DvsaAuthorisationTest\Model;

use DvsaAuthorisation\Model\SiteRole;
use PHPUnit_Framework_TestCase;

class SiteRoleTest extends PHPUnit_Framework_TestCase
{
    public function testGettersAndSetters()
    {
        $name = 'foobar';
        $siteId = 1;
        $siteRole = new SiteRole($name);
        $siteRole->setSiteId($siteId);
        $this->assertEquals($siteId, $siteRole->getSiteId());
    }
}
