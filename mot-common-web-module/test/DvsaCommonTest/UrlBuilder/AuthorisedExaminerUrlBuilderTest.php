<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilder;
use PHPUnit_Framework_TestCase;

/**
 * Class AuthorisedExaminerUrlBuilderTest
 *
 * @package DvsaCommonTest\UrlBuilder
 */
class AuthorisedExaminerUrlBuilderTest extends PHPUnit_Framework_TestCase
{
    const AE_ID = 1;
    const AE_NUMBER = 'A-12345';
    const SITE_NR = 'S00001';
    const LINK_ID = 9999;

    public function testUserAdminUrlBuilder()
    {
        $base = 'authorised-examiner/' . self::AE_ID;

        $this->checkUrl(AuthorisedExaminerUrlBuilder::of(self::AE_ID), $base);

        $this->checkUrl(AuthorisedExaminerUrlBuilder::motTestLog(self::AE_ID), $base . '/mot-test-log');
        $this->checkUrl(AuthorisedExaminerUrlBuilder::motTestLogSummary(self::AE_ID), $base . '/mot-test-log/summary');

        $this->checkUrl(AuthorisedExaminerUrlBuilder::of(self::AE_ID)->slot(), $base . '/slot');
        $this->checkUrl(AuthorisedExaminerUrlBuilder::of(self::AE_ID)->slotUsage(), $base . '/slot-usage');

        $this->checkUrl(
            AuthorisedExaminerUrlBuilder::of(self::AE_ID)->authorisedExaminerPrincipal(),
            $base . '/authorised-examiner-principal'
        );
        $this->checkUrl(AuthorisedExaminerUrlBuilder::of(self::AE_ID)->authorisedExaminerList(), $base . '/list');

        $this->checkUrl(
            AuthorisedExaminerUrlBuilder::of()->authorisedExaminerByNumber(self::AE_NUMBER),
            'authorised-examiner/number/' . self::AE_NUMBER
        );

        $this->checkUrl(
            AuthorisedExaminerUrlBuilder::site(self::AE_ID),
            $base . '/site'
        );
        $this->checkUrl(
            AuthorisedExaminerUrlBuilder::siteLink(self::AE_ID, self::LINK_ID),
            $base . '/site/link/' . self::LINK_ID
        );

        $this->checkUrl(
            AuthorisedExaminerUrlBuilder::status(self::AE_ID),
            $base . '/status'
        );
    }

    private function checkUrl(AuthorisedExaminerUrlBuilder $urlBuilder, $expectUrl)
    {
        $this->assertEquals($expectUrl, $urlBuilder->toString());
        $this->assertInstanceOf(AuthorisedExaminerUrlBuilder::class, $urlBuilder);
    }
}
