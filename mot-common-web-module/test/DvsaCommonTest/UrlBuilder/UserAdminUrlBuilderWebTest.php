<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\AbstractUrlBuilder;
use DvsaCommon\UrlBuilder\UserAdminUrlBuilderWeb;

/**
 * Class UserAdminUrlBuilderWebTest
 * @package DvsaCommonTest\UrlBuilder
 */
class UserAdminUrlBuilderWebTest extends \PHPUnit_Framework_TestCase
{
    const PERSON_ID     = 1;
    const QUESTION_ID   = 1;

    public function testUserAdminUrlBuilder()
    {
        $base = '/user-admin';

        $this->checkUrl(UserAdminUrlBuilderWeb::of()->userSearch(), $base . '/search');
        $this->checkUrl(UserAdminUrlBuilderWeb::of()->userResults(), $base . '/results');
        $this->checkUrl(
            UserAdminUrlBuilderWeb::userProfile(self::PERSON_ID),
            $base . '/user-profile/' . self::PERSON_ID
        );
        $this->checkUrl(
            UserAdminUrlBuilderWeb::userProfileSecurityQuestion(self::PERSON_ID, self::QUESTION_ID),
            $base . '/user-profile/' . self::PERSON_ID . '/security-question/' . self::QUESTION_ID
        );
        $this->checkUrl(
            UserAdminUrlBuilderWeb::userProfileClaimAccount(self::PERSON_ID),
            $base . '/user-profile/' . self::PERSON_ID . '/claim-reset'
        );
        $this->checkUrl(
            UserAdminUrlBuilderWeb::userProfileClaimAccountPost(self::PERSON_ID),
            $base . '/user-profile/' . self::PERSON_ID . '/claim-reset/post'
        );
        $this->checkUrl(
            UserAdminUrlBuilderWeb::userProfileResetPassword(self::PERSON_ID),
            $base . '/user-profile/' . self::PERSON_ID . '/password-reset'
        );
        $this->checkUrl(
            UserAdminUrlBuilderWeb::userProfileRecoverUsername(self::PERSON_ID),
            $base . '/user-profile/' . self::PERSON_ID . '/username-recover'
        );
    }

    private function checkUrl(AbstractUrlBuilder $urlBuilder, $expectUrl)
    {
        $this->assertEquals($expectUrl, $urlBuilder->toString());
        $this->assertInstanceOf(UserAdminUrlBuilderWeb::class, $urlBuilder);
    }
}
