<?php

namespace DvsaCommonTest\Auth\Http;


use DvsaCommon\Auth\Http\AuthorizationBearer;

class AuthorizationBearerTest extends \PHPUnit_Framework_TestCase
{

    public static function data_fromString_valid() {
        return [
            ['Authorization: Bearer 3223bdbdf$*', '3223bdbdf$*'],
            ['Authorization: Bearer  abc123$', 'abc123$']
        ];
    }

    public static function data_fromString_invalid() {
        return [
            ['Authorization: Bearer '],
            ['Authorization: Bearar 23'],
            ['Authorization Bearer 23'],
            ['Authorization Bearer: 23'],
            ['Authorisation: Bearer 23'],
        ];
    }


    /**
     * @dataProvider data_fromString_invalid
     */
    public function testFromString_invalid($headerLine)
    {
        $this->setExpectedException(\Zend\Http\Exception\InvalidArgumentException::class);
        AuthorizationBearer::fromString($headerLine);
    }


    /**
     * @dataProvider data_fromString_valid
     */
    public function testFromString_valid($headerLine, $token)
    {
        $header = AuthorizationBearer::fromString($headerLine);
        $this->assertEquals($header->getToken(), $token);
    }


    public function testToString()
    {
        $header = AuthorizationBearer::fromString('Authorization: Bearer token');
        $this->assertEquals('Authorization: Bearer token', $header->toString());
    }
}
 