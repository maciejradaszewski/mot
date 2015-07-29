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

    public function test()
    {
        $base = '/authorised-examiner/' . self::AE_ID;

        $this->checkUrl(AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID), $base);
        $this->checkUrl(AuthorisedExaminerUrlBuilderWeb::create(), '/authorised-examiner/create');
        $this->checkUrl(AuthorisedExaminerUrlBuilderWeb::createConfirm(), '/authorised-examiner/create/confirmation');

        $this->checkUrl(AuthorisedExaminerUrlBuilderWeb::aeEditStatus(self::AE_ID), '/authorised-examiner/1/edit-status');
        $this->checkUrl(AuthorisedExaminerUrlBuilderWeb::aeEditStatusConfirm(self::AE_ID), '/authorised-examiner/1/edit-status/confirmation');

        $this->checkUrl(AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID)->aeSearch(), $base . '/search');
        $this->checkUrl(AuthorisedExaminerUrlBuilderWeb::aeEdit(self::AE_ID), $base . '/edit');

        $this->checkUrl(AuthorisedExaminerUrlBuilderWeb::motTestLog(self::AE_ID), $base . '/mot-test-log');
        $this->checkUrl(
            AuthorisedExaminerUrlBuilderWeb::motTestLogDownloadCsv(self::AE_ID), $base . '/mot-test-log/csv'
        );

        $this->checkUrl(AuthorisedExaminerUrlBuilderWeb::slots(self::AE_ID), $base . '/slots');

        $this->checkUrl(AuthorisedExaminerUrlBuilderWeb::roles(self::AE_ID), $base . '/roles');
        $this->checkUrl(AuthorisedExaminerUrlBuilderWeb::principals(self::AE_ID), $base . '/principals');
        $this->checkUrl(
            AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID)->principalRemove(8888),
            $base . '/8888/remove-principal-confirmation'
        );

    }

    private function checkUrl(AuthorisedExaminerUrlBuilderWeb $urlBuilder, $expectUrl)
    {
        $this->assertEquals($expectUrl, $urlBuilder->toString());
        $this->assertInstanceOf(AuthorisedExaminerUrlBuilderWeb::class, $urlBuilder);
    }
}
