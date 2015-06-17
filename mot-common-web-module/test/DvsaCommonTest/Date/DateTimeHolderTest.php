<?php

namespace DvsaCommonTest\Date;

use DvsaCommon\Date\DateTimeHolder;

/**
 * Class DateTimeHolderTest
 *
 * @package DvsaCommonTest\Date
 */
class DateTimeHolderTest extends \PHPUnit_Framework_TestCase
{
    public function testGetCurrent()
    {
        $obj = new DateTimeHolder();

        //  --  without millisecond --
        $actualDate = $obj->getCurrent();

        $this->assertInstanceOf(\DateTime::class, $actualDate);
        $this->assertEquals(0, (int)$actualDate->format('u'));

        //  --  with millisecond --
        $actualDate = $obj->getCurrent(true);

        $this->assertInstanceOf(\DateTime::class, $actualDate);
        $this->assertNotEquals(0, (int)$actualDate->format('u'));
    }

    public function testGetCurrentDate()
    {
        $actualDate = (new DateTimeHolder())->getCurrentDate();
        $expectDate = (new \DateTime())->setTime(0, 0, 0);

        $this->assertInstanceOf(\DateTime::class, $actualDate);
        $this->assertEquals($expectDate, $actualDate);
    }

    public function testGetTimestamp()
    {
        $dth = (new DateTimeHolder());

        //  --  as string   --
        $actual = $dth->getTimestamp();

        $this->assertTrue(is_string($actual));
        $this->assertCount(2, preg_split('/\s/', $actual));

        //  --  as float    --
        $now = microtime(true);
        $actual = $dth->getTimestamp(true);

        $this->assertLessThanOrEqual($actual, $now);
        $this->assertLessThanOrEqual(microtime(true), $actual);
        $this->assertTrue(is_float($actual));
    }
}
