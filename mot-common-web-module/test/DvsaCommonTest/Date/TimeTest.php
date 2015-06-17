<?php
namespace DvsaCommonTest\Date;

use DvsaCommon\Date\Time;

/**
 * Class TimeTest
 *
 * @package DvsaCommonTest\Date
 */
class TimeTest extends \PHPUnit_Framework_TestCase
{
    public function testEquals_timesAreTheSame_shouldReturnTrue()
    {
        $timeA = new Time(5, 4, 6);
        $timeB = new Time(5, 4, 6);

        $this->assertEquals($timeA, $timeB);
        $this->assertTrue($timeA->equals($timeB));
    }

    public function testEquals_timesAreDifferent_shouldReturnFalse()
    {
        $timeA = new Time(5, 4, 6);
        $timeB = new Time(5, 4, 13);

        $this->assertNotEquals($timeA, $timeB);
        $this->assertFalse($timeA->equals($timeB));
    }

    // Greater than
    public function testGreater_timeIsGreater_shouldReturnTrue()
    {
        $greaterTime = new Time(5, 0, 0);
        $lesserTime = new Time(1, 0, 0);

        $this->assertTrue($greaterTime->greaterThan($lesserTime));
    }

    public function testGreater_timeIsLess_shouldReturnFalse()
    {
        $greaterTime = new Time(5, 0, 0);
        $lesserTime = new Time(1, 0, 0);

        $this->assertFalse($lesserTime->greaterThan($greaterTime));
    }

    public function testGreater_timeIsEqual_shouldReturnFalse()
    {
        $timeA = new Time(5, 4, 6);
        $timeB = new Time(5, 4, 6);

        $this->assertFalse($timeA->greaterThan($timeB));
    }

    // Greater equal than
    public function testGreaterEqualThan_timeIsGreater_shouldReturnTrue()
    {
        $greaterTime = new Time(5, 0, 0);
        $lesserTime = new Time(1, 0, 0);

        $this->assertTrue($greaterTime->greaterEqualThan($lesserTime));
    }

    public function testGreaterEqualThan_timeIsLesser_shouldReturnFalse()
    {
        $greaterTime = new Time(5, 0, 0);
        $lesserTime = new Time(1, 0, 0);

        $this->assertFalse($lesserTime->greaterEqualThan($greaterTime));
    }

    public function testGreaterEqualThan_timeIsEqual_shouldReturnTrue()
    {
        $timeA = new Time(5, 4, 6);
        $timeB = new Time(5, 4, 6);

        $this->assertTrue($timeA->greaterEqualThan($timeB));
    }

    // Lesser than
    public function testLesserThan_timeIsLesser_shouldReturnTrue()
    {
        $greaterTime = new Time(5, 0, 0);
        $lesserTime = new Time(1, 0, 0);

        $this->assertTrue($lesserTime->lesserThan($greaterTime));
    }

    public function testLesserThan_timeIsGreater_shouldReturnFalse()
    {
        $greaterTime = new Time(5, 0, 0);
        $lesserTime = new Time(1, 0, 0);

        $this->assertFalse($greaterTime->lesserThan($lesserTime));
    }

    public function testLesserThan_timeIsEqual_shouldReturnFalse()
    {
        $timeA = new Time(5, 4, 6);
        $timeB = new Time(5, 4, 6);

        $this->assertFalse($timeA->lesserThan($timeB));
    }

    // Lesser equal than
    public function testLesserEqual_timeIsLesser_shouldReturnTrue()
    {
        $greaterTime = new Time(5, 0, 0);
        $lesserTime = new Time(1, 0, 0);

        $this->assertTrue($lesserTime->lesserEqualThan($greaterTime));
    }

    public function testLesserEqualThan_timeIsGreater_shouldReturnFalse()
    {
        $greaterTime = new Time(5, 0, 0);
        $lesserTime = new Time(1, 0, 0);

        $this->assertFalse($greaterTime->lesserEqualThan($lesserTime));
    }

    public function testLesserEqualThan_timeIsEqual_shouldReturnTrue()
    {
        $timeA = new Time(5, 4, 6);
        $timeB = new Time(5, 4, 6);

        $this->assertTrue($timeA->lesserEqualThan($timeB));
    }

    // Convert to and from datetime
    public function testFromDateTime_shouldReturnValidTimeObject()
    {
        $expectedTime = new Time(16, 12, 0);

        $dateTime = new \DateTime();
        $dateTime->setTime(16, 12, 0);

        $convertedTime = Time::fromDateTime($dateTime);

        $this->assertEquals($expectedTime, $convertedTime);
    }

    public function testIsValid_invalidHours_shouldReturnFalse()
    {
        $this->assertFalse(Time::isValid(25, 12, 0));
    }

    public function testIsValid_invalidMinutes_shouldReturnFalse()
    {
        $this->assertFalse(Time::isValid(23, 60, 0));
    }

    public function testIsValid_invalidSeconds_shouldReturnFalse()
    {
        $this->assertFalse(Time::isValid(23, 1, 60));
    }

    public function testNow_returnsTime()
    {
        $this->assertInstanceOf(Time::class, Time::now());
    }

    public function testToIso8601_returnValidFormat()
    {
        $time = new Time(16, 12, 0);
        $this->assertEquals("16:12:00", $time->toIso8601());
    }

    public function testFormat_returnValidFormat()
    {
        $time = new Time(16, 12, 0);
        $this->assertEquals("16:12", $time->format('G:i'));
    }

    public function testConstructor_invalidFormat_shouldThrowException()
    {
        $this->setExpectedException('InvalidArgumentException');
        new Time(25, 12, 0);
    }

    public function testFromTimestamp_validTimestamp_shouldReturnValidTimeObject()
    {
        $time = Time::fromTimestamp("6432");
        $this->assertEquals($time->getHour(), 1);
        $this->assertEquals($time->getMinute(), 47);
        $this->assertEquals($time->getSecond(), 12);
    }

    public function testFromTimestamp_invalidTimestamp_shouldThrowException()
    {
        $this->setExpectedException('InvalidArgumentException');
        Time::fromTimestamp(86400);
    }

    public function testFromIso8601_validIso8601_shouldReturnValidTimeObject()
    {
        $time = Time::fromIso8601("23:59:59");
        $this->assertEquals($time->getHour(), 23);
        $this->assertEquals($time->getMinute(), 59);
        $this->assertEquals($time->getSecond(), 59);
    }

    public function testFromIso8601_invalidIso8601_shouldReturnValidTimeObject()
    {
        $this->setExpectedException('InvalidArgumentException');
        Time::fromIso8601("23:59:60");
    }

    public function testIsAm_givenMorningTime_ShouldReturnTrue(){
        $time = Time::fromIso8601("09:00:00");
        $this->assertTrue($time->isAm());
    }

    public function testIsPm_givenMorningTime_ShouldReturnFalse(){
        $time = Time::fromIso8601("09:00:00");
        $this->assertFalse($time->isPm());
    }

    public function testIsPm_givenEveningTime_ShouldReturnTrue(){
        $time = Time::fromIso8601("17:00:00");
        $this->assertTrue($time->isPm());
    }

    public function testIsAm_givenEveningTime_ShouldReturnFalse(){
        $time = Time::fromIso8601("17:00:00");
        $this->assertFalse($time->isAm());
    }

    public function testIsAm_givenNoonTime_shouldReturnFalse()
    {
        $time = Time::fromIso8601("12:00:00");
        $this->assertFalse($time->isAm());
    }
    public function testIsPm_givenNoonTime_shouldReturnTrue()
    {
        $time = Time::fromIso8601("12:00:00");
        $this->assertTrue($time->isPm());
    }

    public function testIsAm_givenMidnightTime_shouldReturnTrue()
    {
        $time = Time::fromIso8601("00:00:00");
        $this->assertTrue($time->isAm());
    }

    public function testIsPm_givenMidnightTime_shouldReturnFalse()
    {
        $time = Time::fromIso8601("00:00:00");
        $this->assertFalse($time->isPm());
    }

    public function testToTimestamp24_midnight()
    {
        $time = Time::fromIso8601("00:00:00");
        $this->assertEquals(24 * 3600, $time->toTimestamp24());
    }

    public function testToTimestamp24_nonMidnight()
    {
        $time = Time::fromIso8601("00:00:01");
        $this->assertEquals(1, $time->toTimestamp24());
    }

    public function testToTimestamp_shouldBeFormattedForCurrentDay()
    {
        $time = Time::fromIso8601('16:40:01');
        $this->assertSame((new \DateTime())->format('N'), $time->format('N'));
        $this->assertSame('16:40:01', $time->format('H:i:s'));
    }
}
