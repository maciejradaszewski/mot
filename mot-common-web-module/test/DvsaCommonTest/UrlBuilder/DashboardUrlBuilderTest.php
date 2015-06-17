<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\DashboardUrlBuilder;

/**
 * unit tests for DashboardUrlBuilder
 */
class DashboardUrlBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function test_dashboard_withId_shouldBeOk()
    {
        $this->assertSame('person/1/dashboard', DashboardUrlBuilder::dashboard(1)->toString());
    }

    public function test_userStats_withId_shouldBeOk()
    {
        $this->assertSame('person/1/stats', DashboardUrlBuilder::userStats(1)->toString());
    }
}
