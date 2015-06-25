<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\UrlBuilderWeb;

class UrlBuilderWebTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $this->assertEquals('/mot-test-log', UrlBuilderWeb::of()->motTestLogs()->toString());
        $this->assertEquals('/mot-test-log/csv', UrlBuilderWeb::of()->motTestLogDownloadCsv()->toString());
        $this->assertEquals('/replacement-certificate/2', UrlBuilderWeb::of()->replacementCertificate(2)->toString());
        $this->assertEquals('/replacement-certificate/finish/100', UrlBuilderWeb::of()->replacementCertificateFinish(100)->toString());
        $this->assertEquals('/replacement-certificate/5/summary', UrlBuilderWeb::of()->replacementCertificateSummary(5)->toString());
    }

}
