<?php

namespace DvsaCommonTest\Time;

use DvsaCommon\Date\Time;
use PHPUnit_Framework_TestCase;

class TimeUConversionTest extends PHPUnit_Framework_TestCase
{
    public function testTimestampToTime_givenTimeInSeconds_shouldReturnTime()
    {
        $time = Time::fromTimestamp(0);
        $this->assertEquals( "00:00:00", $time->toIso8601());

        $time = Time::fromTimestamp(00);
        $this->assertEquals("00:00:00", $time->toIso8601());

        $time = Time::fromTimestamp(000);
        $this->assertEquals("00:00:00", $time->toIso8601());

        $time = Time::fromTimestamp(0000);
        $this->assertEquals("00:00:00", $time->toIso8601());

        $time = Time::fromTimestamp(32400);
        $this->assertEquals("09:00:00", $time->toIso8601());

        $time = Time::fromTimestamp(34200);
        $this->assertEquals("09:30:00", $time->toIso8601());

        $time = Time::fromTimestamp(45000);
        $this->assertEquals("12:30:00", $time->toIso8601());

        $time = Time::fromTimestamp(43200);
        $this->assertEquals("12:00:00", $time->toIso8601());
    }

    public function testTimeToInt_givenIsoTime_shouldReturnIntTimeInSeconds()
    {
        $time = Time::fromIso8601("09:00:00");
        $this->assertEquals("32400", $time->toTimestamp());

        $time = Time::fromIso8601("09:30:00");
        $this->assertEquals("34200", $time->toTimestamp());

        $time = Time::fromIso8601("12:30:00");
        $this->assertEquals("45000", $time->toTimestamp());

        $time = Time::fromIso8601("12:00:00");
        $this->assertEquals("43200", $time->toTimestamp());

        $time = Time::fromIso8601("00:00:00");
        $this->assertEquals("0", $time->toTimestamp());
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Cannot create time from timestamp: '12:00:00'
     */
    public function testFromTimestamp_givenInvalidTimestamp_shouldThrowException()
    {
        Time::fromTimestamp("12:00:00");
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Cannot create time from timestamp: '86400'
     */
    public function testFromTimestamp_givenOutOfBoundsTimestamp_shouldThrowException()
    {
        Time::fromTimestamp(86400);
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Cannot create time from timestamp: '-1'
     */
    public function testFromTimestamp_givenNegative_shouldThrowException()
    {
        Time::fromTimestamp(-1);
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Cannot create time from timestamp: 'apple'
     */
    public function testFromTimestamp_givenCharacters_shouldThrowException()
    {
        Time::fromTimestamp("apple");
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Invalid value " 00 " provided. Provide 00:00:00 format.
     */
    public function testFromIso_givenInvalidTime_shouldThrowException()
    {
        Time::fromIso8601("00");
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Invalid value " 24:00:00 " provided. Provide 00:00:00 format.
     */
    public function testFromIso_givenOutOfBoundsHour_shouldThrowException()
    {
        Time::fromIso8601("24:00:00");
    }

    /**
     * @expectedException        \InvalidArgumentException
     * @expectedExceptionMessage Invalid value " 15:60:00 " provided. Provide 00:00:00 format.
     */
    public function testFromIso_givenOutOfBoundsMins_shouldThrowException()
    {
        Time::fromIso8601("15:60:00");
    }

}
