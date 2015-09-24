<?php


namespace DvsaCommonTest\Enum;

use DvsaCommon\Enum\EventTypeCode;

class EventTypeCodeTest extends \PHPUnit_Framework_TestCase
{
    public function testGetAll()
    {
        $this->assertInternalType('array', EventTypeCode::getAll());
    }

    public function testExists()
    {
        $this->assertTrue(EventTypeCode::exists(EventTypeCode::APPEAL_AGAINST_DISCIPLINARY_ACTION));
        $this->assertFalse(EventTypeCode::exists('invalid'));
    }
}
