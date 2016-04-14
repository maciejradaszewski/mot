<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\OrganisationUrlBuilder;
use DvsaCommon\UrlBuilder\QualificationDetailsUrlBuilder;
use PHPUnit_Framework_TestCase;

class QualificationDetailsUrlBuilderTest extends PHPUnit_Framework_TestCase
{
    public function test()
    {
        $this->checkUrl(QualificationDetailsUrlBuilder::demoTestRequests(), 'demo-test-requests');
    }

    private function checkUrl(QualificationDetailsUrlBuilder $urlBuilder, $expectUrl)
    {
        $this->assertEquals($expectUrl, $urlBuilder->toString());
        $this->assertInstanceOf(QualificationDetailsUrlBuilder::class, $urlBuilder);
    }
}
