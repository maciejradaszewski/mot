<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\AbstractUrlBuilder;
use DvsaCommon\UrlBuilder\PersonUrlBuilderWeb;

/**
 * Tests for PersonUrlBuilderWebTest.
 */
class PersonUrlBuilderWebTest extends \PHPUnit_Framework_TestCase
{
    const USER_ID   = 99999;
    const QUESTION2 = 2;

    public function testPersonUrlBuilder()
    {
        $this->checkUrl(PersonUrlBuilderWeb::home(), '/');
        $this->checkUrl(PersonUrlBuilderWeb::stats(), '/stats');
        $this->checkUrl(PersonUrlBuilderWeb::myAppl(), '/my-applications');
        $this->checkUrl(PersonUrlBuilderWeb::profile(self::USER_ID), '/profile/' . self::USER_ID);
        $this->checkUrl(PersonUrlBuilderWeb::profileEdit(), '/profile/edit');
        $this->checkUrl(
            PersonUrlBuilderWeb::updateAuthMotTesting(self::USER_ID), '/profile/' . self::USER_ID . '/mot-testing'
        );
        $this->checkUrl(PersonUrlBuilderWeb::securitySettings(), '/profile/security-settings');
        $this->checkUrl(PersonUrlBuilderWeb::securityQuestions(), '/profile/security-question');
        $this->checkUrl(PersonUrlBuilderWeb::securityQuestions(self::QUESTION2), '/profile/security-question/2');
    }

    private function checkUrl(AbstractUrlBuilder $urlBuilder, $expectUrl)
    {
        $this->assertEquals($expectUrl, $urlBuilder->toString());
        $this->assertInstanceOf(PersonUrlBuilderWeb::class, $urlBuilder);
    }
}
