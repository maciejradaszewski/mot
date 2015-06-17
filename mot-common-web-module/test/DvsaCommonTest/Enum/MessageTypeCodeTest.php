<?php


namespace DvsaCommonTest\Enum;

use DvsaCommon\Enum\MessageTypeCode;

class MessageTypeCodeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAll()
    {
        $result = MessageTypeCode::getAll();

        $this->assertTrue(is_array($result));
    }

    public function testExists()
    {
        $this->assertTrue(MessageTypeCode::exists(MessageTypeCode::PASSWORD_RESET_BY_EMAIL));
        $this->assertFalse(MessageTypeCode::exists('invalid'));
    }
}
