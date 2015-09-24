<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\AbstractUrlBuilder;
use DvsaCommon\UrlBuilder\MotTestUrlBuilderWeb;

class MotTestUrlBuilderWebTest extends \PHPUnit_Framework_TestCase
{
    const MOT_TEST_NR = '123456798';
    const TEST_TYPE_CODE = 'NT';
    const VEHICLE_ID = 9999;

    public function test()
    {
        $base = '/mot-test/' . self::MOT_TEST_NR;
        $this->checkUrl(MotTestUrlBuilderWeb::motTest(self::MOT_TEST_NR), $base);

        $this->checkUrl(MotTestUrlBuilderWeb::printResult(self::MOT_TEST_NR), $base . '/print-test-result');
        $this->checkUrl(
            MotTestUrlBuilderWeb::printDuplicateResult(self::MOT_TEST_NR), $base . '/print-duplicate-test-result'
        );
        $this->checkUrl(MotTestUrlBuilderWeb::printCertificate(self::MOT_TEST_NR), $base . '/print-certificate');
        $this->checkUrl(MotTestUrlBuilderWeb::summary(self::MOT_TEST_NR), $base . '/test-summary');

        $this->checkUrl(MotTestUrlBuilderWeb::abortSuccess(self::MOT_TEST_NR), $base . '/abort-success');
        $this->checkUrl(MotTestUrlBuilderWeb::abortFail(self::MOT_TEST_NR), $base . '/abort-fail');

        $this->checkUrl(MotTestUrlBuilderWeb::cancel(self::MOT_TEST_NR), $base . '/cancel');
        $this->checkUrl(MotTestUrlBuilderWeb::cancelled(self::MOT_TEST_NR), $base . '/cancelled');

        $this->checkUrl(MotTestUrlBuilderWeb::options(self::MOT_TEST_NR), $base . '/options');

        $refuseBase = '/refuse-to-test/' . self::TEST_TYPE_CODE . '/' . self::VEHICLE_ID;
        $this->checkUrl(
            MotTestUrlBuilderWeb::refuseReason(self::TEST_TYPE_CODE, self::VEHICLE_ID), $refuseBase . '/reason'
        );
        $this->checkUrl(
            MotTestUrlBuilderWeb::refuseSummary(self::TEST_TYPE_CODE, self::VEHICLE_ID), $refuseBase . '/summary'
        );
        $this->checkUrl(
            MotTestUrlBuilderWeb::refusePrint(self::TEST_TYPE_CODE, self::VEHICLE_ID), $refuseBase . '/print'
        );
    }

    private function checkUrl(AbstractUrlBuilder $urlBuilder, $expectUrl)
    {
        $this->assertEquals($expectUrl, $urlBuilder->toString());
        $this->assertInstanceOf(MotTestUrlBuilderWeb::class, $urlBuilder);
    }
}
