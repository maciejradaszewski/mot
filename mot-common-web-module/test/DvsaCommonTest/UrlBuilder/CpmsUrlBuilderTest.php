<?php

namespace DvsaCommonTest\UrlBuilder;

use DvsaCommon\UrlBuilder\CpmsUrlBuilder;
use PHPUnit_Framework_TestCase as TestCase;

/**
 * Test for CpmsUrlBuilder
 */
class CpmsUrlBuilderTest extends TestCase
{
    public function testMandateShouldReturnFullUrl()
    {
        $baseUri = 'http://dev.null/';
        $cpmsUrlBuilder = new CpmsUrlBuilder($baseUri);

        $result = $cpmsUrlBuilder->mandate()->toString();

        $expected = 'http://dev.null/api/mandate';
        $this->assertEquals($expected, $result);
    }

    public function testMandateShouldReturnUrlWithToken()
    {
        $baseUri = '_uri_';
        $cpmsUrlBuilder = new CpmsUrlBuilder($baseUri);

        $result = $cpmsUrlBuilder->mandate('_token_');

        $expected = '_uri_/api/mandate/_token_';
        $this->assertEquals($expected, $result);
    }
}
