<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\OrganisationUrlBuilder;
use PHPUnit_Framework_TestCase;

/**
 * Class OrganisationPositionUrlBuilderTest
 *
 * @package DvsaCommonTest\UrlBuilder
 */
class OrganisationUrlBuilderTest extends PHPUnit_Framework_TestCase
{
    public function test_organisationPosition_shouldBeOk()
    {
        $this->assertSame('organisation/1', OrganisationUrlBuilder::organisationById(1)->toString());
    }

    public function test_organisationPosition_removeRole_shouldBeOk()
    {
        $this->assertSame(
            'organisation/1/position/1',
            OrganisationUrlBuilder::organisationById(1)->position()->routeParam('positionId', 1)->toString()
        );
    }
}
