<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\SiteUrlBuilder;

/**
 * Class SiteUrlBuilderTest
 *
 * @package DvsaCommonTest\UrlBuilder
 */
class SiteUrlBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function test_sitePosition_shouldBeOk()
    {
        $this->assertSame('site/1', SiteUrlBuilder::site(1)->toString());
    }

    public function test_sitePosition_removeRole_shouldBeOk()
    {
        $this->assertSame(
            'site/1/position/1',
            SiteUrlBuilder::site(1)->position()->routeParam('positionId', 1)->toString()
        );
    }
}
