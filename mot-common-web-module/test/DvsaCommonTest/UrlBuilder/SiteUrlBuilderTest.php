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
    const BASE = 'site';
    const ID = 42;

    public function testSite()
    {
        $expected = $this->baseWithId();
        $actual = SiteUrlBuilder::site(self::ID);
        $this->assertSame($expected, $actual->toString());
    }

    public function testCreateEvent()
    {
        $expected = $this->baseWithId().SiteUrlBuilder::EVENT;
        $actual = SiteUrlBuilder::site(self::ID)->createEvent();
        $this->assertSame($expected, $actual->toString());
    }

    public function testUsage()
    {
        $expected = $this->baseWithId().SiteUrlBuilder::USAGE;
        $actual = SiteUrlBuilder::site(self::ID)->usage();
        $this->assertSame($expected, $actual->toString());
    }

    public function testPeriodData()
    {
        $expected = $this->baseWithId().SiteUrlBuilder::USAGE.SiteUrlBuilder::USAGE_PERIOD_DATA;
        $actual = SiteUrlBuilder::site(self::ID)->usage()->periodData();
        $this->assertSame($expected, $actual->toString());
    }

    public function test_sitePosition_removeRole_shouldBeOk()
    {
        $expected = $this->baseWithId().'/position/1';
        $this->assertSame(
            $expected,
            SiteUrlBuilder::site(self::ID)->position()->routeParam('positionId', 1)->toString()
        );
    }

    private function baseWithId()
    {
        return self::BASE.'/'.self::ID;
    }

}
