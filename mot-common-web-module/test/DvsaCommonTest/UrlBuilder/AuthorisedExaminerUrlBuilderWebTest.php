<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;

/**
 * Class AuthorisedExaminerUrlBuilderWebTest
 *
 * @package DvsaCommonTest\UrlBuilder
 */
class AuthorisedExaminerUrlBuilderWebTest extends \PHPUnit_Framework_TestCase
{
    const AE_ID = 1;
    const AE_NUMBER = 'A12345';
    const SITE_NR = 'S00001';
    const PRINCIPAL_ID = 6666;
    const AE_SITE_LINK_ID = 999999;

    public function test()
    {
        $base = '/authorised-examiner/' . self::AE_ID;

        $this->checkUrl(AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID), $base);
        $this->checkUrl(AuthorisedExaminerUrlBuilderWeb::create(), '/authorised-examiner/create');
        $this->checkUrl(AuthorisedExaminerUrlBuilderWeb::createConfirm(), '/authorised-examiner/create/confirmation');

        $this->checkUrl(AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID)->aeSearch(), $base . '/search');

        $this->checkUrl(AuthorisedExaminerUrlBuilderWeb::motTestLog(self::AE_ID), $base . '/mot-test-log');
        $this->checkUrl(
            AuthorisedExaminerUrlBuilderWeb::motTestLogDownloadCsv(self::AE_ID), $base . '/mot-test-log/csv'
        );

        $this->checkUrl(AuthorisedExaminerUrlBuilderWeb::slots(self::AE_ID), $base . '/slots');

        $this->checkUrl(AuthorisedExaminerUrlBuilderWeb::roles(self::AE_ID), $base . '/roles');
        $this->checkUrl(AuthorisedExaminerUrlBuilderWeb::principals(self::AE_ID), $base . '/principals');
        $this->checkUrl(
            AuthorisedExaminerUrlBuilderWeb::principalRemove(self::AE_ID, self::PRINCIPAL_ID),
            $base . '/' . self::PRINCIPAL_ID . '/remove-principal-confirmation'
        );

        $this->checkUrl(AuthorisedExaminerUrlBuilderWeb::siteLink(self::AE_ID), $base . '/site/link');
        $this->checkUrl(
            AuthorisedExaminerUrlBuilderWeb::siteUnlink(self::AE_ID, self::AE_SITE_LINK_ID),
            $base . '/site/unlink/' . self::AE_SITE_LINK_ID
        );
    }

    private function checkUrl(AuthorisedExaminerUrlBuilderWeb $urlBuilder, $expectUrl)
    {
        $this->assertEquals($expectUrl, $urlBuilder->toString());
        $this->assertInstanceOf(AuthorisedExaminerUrlBuilderWeb::class, $urlBuilder);
    }
}
