<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\AuthorisedExaminerUrlBuilder;
use PHPUnit_Framework_TestCase;

/**
 * Class AuthorisedExaminerUrlBuilderTest
 * @package DvsaCommonTest\UrlBuilder
 */
class AuthorisedExaminerUrlBuilderTest extends PHPUnit_Framework_TestCase
{
    const AE_ID     = 1;
    const AE_NUMBER = 'A-12345';

    public function testUserAdminUrlBuilder()
    {
        $urlBuilder = new AuthorisedExaminerUrlBuilder(self::AE_ID);

        $this->assertEquals(
            'authorised-examiner/' . self::AE_ID . '/slot',
            $urlBuilder->slot()
        );
        $urlBuilder = AuthorisedExaminerUrlBuilder::of(self::AE_ID);
        $this->assertInstanceOf(AuthorisedExaminerUrlBuilder::class, $urlBuilder);
        $this->assertEquals('authorised-examiner/' . self::AE_ID . '/slot-usage', $urlBuilder->slotUsage()->toString());
        $this->assertEquals('authorised-examiner/' . self::AE_ID . '/authorised-examiner-principal', AuthorisedExaminerUrlBuilder::of(self::AE_ID)->authorisedExaminerPrincipal()->toString());
        $this->assertEquals('authorised-examiner/' . self::AE_ID . '/list', AuthorisedExaminerUrlBuilder::of(self::AE_ID)->authorisedExaminerList()->toString());
        $this->assertEquals('authorised-examiner/' . self::AE_ID . '/mot-test-log', AuthorisedExaminerUrlBuilder::of(self::AE_ID)->motTestLog(self::AE_ID)->toString());
        $this->assertEquals('authorised-examiner/' . self::AE_ID . '/mot-test-log/summary', AuthorisedExaminerUrlBuilder::of(self::AE_ID)->motTestLog(self::AE_ID)->motTestLogSummary(self::AE_ID)->toString());
        $this->assertEquals('authorised-examiner/number/A-12345', AuthorisedExaminerUrlBuilder::of()->authorisedExaminerByNumber(self::AE_NUMBER)->toString());
    }

}
