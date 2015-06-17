<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\AbstractUrlBuilder;
use DvsaCommon\UrlBuilder\SiteUrlBuilderWeb;

class SiteUrlBuilderWebTest extends \PHPUnit_Framework_TestCase
{
    const SITE_ID = 1;
    const POSITION_ID = 1;

    public function test()
    {
        $base = '/vehicle-testing-station';
        $this->checkUrl(SiteUrlBuilderWeb::of(), $base);
        $this->checkUrl(SiteUrlBuilderWeb::of(self::SITE_ID), $base . '/' . self::SITE_ID);
        $this->checkUrl(SiteUrlBuilderWeb::search(), $base . '/search');
        $this->checkUrl(SiteUrlBuilderWeb::result(), $base . '/result');
        $this->checkUrl(SiteUrlBuilderWeb::removeRole(self::SITE_ID, self::POSITION_ID), $base . '/1/remove-role/1');
    }

    private function checkUrl(AbstractUrlBuilder $urlBuilder, $expectUrl)
    {
        $this->assertEquals($expectUrl, $urlBuilder->toString());
        $this->assertInstanceOf(SiteUrlBuilderWeb::class, $urlBuilder);
    }
}
