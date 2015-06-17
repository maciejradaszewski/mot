<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\AbstractUrlBuilder;
use DvsaCommon\UrlBuilder\UserAdminUrlBuilder;
use PHPUnit_Framework_TestCase;

/**
 * Class UserAdminUrlBuilderTest
 * @package DvsaCommonTest\UrlBuilder
 */
class UserAdminUrlBuilderTest extends PHPUnit_Framework_TestCase
{
    const PERSON_ID     = 1;
    const QUESTION_ID   = 1;

    public function testUserAdminUrlBuilder()
    {
        $this->checkUrl(
            UserAdminUrlBuilder::securityQuestionCheck(self::QUESTION_ID, self::PERSON_ID),
            'security-question/check/' . self::QUESTION_ID . '/' . self::PERSON_ID
        );

        $this->checkUrl(
            UserAdminUrlBuilder::securityQuestionGet(self::QUESTION_ID, self::PERSON_ID),
            'security-question/get/' . self::QUESTION_ID . '/' . self::PERSON_ID
        );
    }

    private function checkUrl(AbstractUrlBuilder $urlBuilder, $expectUrl)
    {
        $this->assertEquals($expectUrl, $urlBuilder->toString());
        $this->assertInstanceOf(UserAdminUrlBuilder::class, $urlBuilder);
    }
}
