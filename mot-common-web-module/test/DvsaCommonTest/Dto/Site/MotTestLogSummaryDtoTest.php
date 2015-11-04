<?php

namespace DvsaCommonTest\Dto\Site;

use DvsaCommon\Dto\Site\MotTestLogSummaryDto;

/**
 * Unit tests for MotTestLogSummaryDto.
 */
class MotTestLogSummaryDtoTest extends \PHPUnit_Framework_TestCase
{
    const YEAR = '1024';
    const MONTH = '256';
    const WEEK = '12';
    const DAY = '2';

    protected $dtoClassName = MotTestLogSummaryDto::class;

    public function testGetters()
    {
        $motTestLogDto = new MotTestLogSummaryDto();

        $motTestLogDto
            ->setYear(self::YEAR)
            ->setMonth(self::MONTH)
            ->setWeek(self::WEEK)
            ->setToday(self::DAY);

        $this->assertEquals((int) self::YEAR, $motTestLogDto->getYear());
        $this->assertEquals((int) self::MONTH, $motTestLogDto->getMonth());
        $this->assertEquals((int) self::WEEK, $motTestLogDto->getWeek());
        $this->assertEquals((int) self::DAY, $motTestLogDto->getToday());
    }
}
