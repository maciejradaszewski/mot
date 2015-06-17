<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilder;
use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilderWeb;
use PHPUnit_Framework_TestCase;

/**
 * Class AuthorisedExaminerUrlBuilderWebTest
 * @package DvsaCommonTest\UrlBuilder
 */
class AuthorisedExaminerUrlBuilderWebTest extends PHPUnit_Framework_TestCase
{
    const AE_ID     = 1;
    const AE_NUMBER = 'A12345';

    public function testUserAdminUrlBuilder()
    {
        $urlBuilder = new AuthorisedExaminerUrlBuilderWeb(self::AE_ID);

        $this->assertEquals(
            '/authorised-examiner/' . self::AE_ID . '/create',
            $urlBuilder->aeCreate()->toString()
        );
        $urlBuilder = AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID);
        $this->assertInstanceOf(AuthorisedExaminerUrlBuilderWeb::class, $urlBuilder);
        $this->assertEquals('/authorised-examiner/' . self::AE_ID . '/search', $urlBuilder->aeSearch()->toString());
        $this->assertEquals('/authorised-examiner/' . self::AE_ID . '/edit', AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID)->aeEdit()->toString());
        $this->assertEquals('/authorised-examiner/' . self::AE_ID . '/mot-test-log', AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID)->motTestLog(self::AE_ID)->toString());
        $this->assertEquals('/authorised-examiner/' . self::AE_ID . '/mot-test-log/download/csv', AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID)->motTestLogDownloadCsv(self::AE_ID)->toString());
        $this->assertEquals('/authorised-examiner/' . self::AE_ID . '/slots', AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID)->slots(self::AE_ID)->toString());
        $this->assertEquals('/authorised-examiner/' . self::AE_ID . '/slots/usage', AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID)->slotsUsage(self::AE_ID)->toString());
        $this->assertEquals('/authorised-examiner/' . self::AE_ID . '/slots/usage/page/1', AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID)->slotsUsage(self::AE_ID, 1)->toString());
        $this->assertEquals('/authorised-examiner/' . self::AE_ID . '/slots/usage/page/1', AuthorisedExaminerUrlBuilderWeb::of(self::AE_ID)->slotsUsage(self::AE_ID, 1, 1)->toString());
    }

}
