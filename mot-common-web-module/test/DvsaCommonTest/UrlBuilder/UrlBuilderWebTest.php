<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\UrlBuilderWeb;

class UrlBuilderWebTest extends \PHPUnit_Framework_TestCase
{
    public function test()
    {
        $this->assertEquals('/mot-test-log', UrlBuilderWeb::of()->motTestLogs()->toString());
        $this->assertEquals('/mot-test-log/csv', UrlBuilderWeb::of()->motTestLogDownloadCsv()->toString());
        $this->assertEquals('/mot-test/42/replacement-certificate/2', UrlBuilderWeb::of()->replacementCertificate(2, 42)->toString());
        $this->assertEquals('/mot-test/100/replacement-certificate/finish', UrlBuilderWeb::of()->replacementCertificateFinish(100)->toString());
        $this->assertEquals('/mot-test/42/replacement-certificate/5/summary', UrlBuilderWeb::of()->replacementCertificateSummary(5, 42)->toString());
    }

}
