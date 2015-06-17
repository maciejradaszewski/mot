<?php

namespace DvsaCommonTest\Dto\Common;

use DvsaCommon\Dto\Common\DateDto;

/**
 * Class DateDtoTest
 */
class DateDtoTest extends \PHPUnit_Framework_TestCase
{
    const YEAR1 = '2014';
    const MONTH1 = '01';
    const DAY1 = '01';
    const TEST_DATE1 = '01-01-2014';
    const TEST_DATE2= '02-02-2013';
    const YEAR2 = '2013';
    const MONTH2 = '02';
    const DAY2 = '02';

    public function testDateDtoCreate()
    {
        $dateDto = new DateDto(self::YEAR1, self::MONTH1, self::DAY1);

        $this->assertSame(self::YEAR1, $dateDto->getYear());
        $this->assertSame(self::MONTH1, $dateDto->getMonth());
        $this->assertSame(self::DAY1, $dateDto->getDay());
        $this->assertInstanceOf(\DateTime::class, $dateDto->getDate());
        $this->assertSame(self::YEAR1, $dateDto->getDate()->format('Y'));
        $this->assertSame(self::MONTH1, $dateDto->getDate()->format('m'));
        $this->assertSame(self::DAY1, $dateDto->getDate()->format('d'));

        $dateDto->setDate(new \DateTime(self::TEST_DATE2));
        $this->assertInstanceOf(\DateTime::class, $dateDto->getDate());
        $this->assertSame(self::YEAR2, $dateDto->getDate()->format('Y'));
        $this->assertSame(self::MONTH2, $dateDto->getDate()->format('m'));
        $this->assertSame(self::DAY2, $dateDto->getDate()->format('d'));
    }
}
