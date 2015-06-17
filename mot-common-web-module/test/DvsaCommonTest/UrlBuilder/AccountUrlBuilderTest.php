<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\AbstractUrlBuilder;
use DvsaCommon\UrlBuilder\AccountUrlBuilder;

class AccountUrlBuilderTest extends \PHPUnit_Framework_TestCase
{
    const TOKEN = 12332;
    const USERNAME = 'tester1';

    public function test()
    {
        $base = 'reset-password';
        $this->checkUrl(AccountUrlBuilder::resetPassword(), $base);
        $this->checkUrl(AccountUrlBuilder::resetPassword(self::TOKEN), $base . '/' . self::TOKEN);
        $this->checkUrl(AccountUrlBuilder::validateUsername(), $base . '/validate-username');
    }

    private function checkUrl(AbstractUrlBuilder $urlBuilder, $expectUrl)
    {
        $this->assertEquals($expectUrl, $urlBuilder->toString());
        $this->assertInstanceOf(AccountUrlBuilder::class, $urlBuilder);
    }
}
