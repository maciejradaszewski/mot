<?php

namespace DvsaCommonTest\Date;

use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Date\DateTimeDisplayFormat;

/**
 *
 */
class DateTimeApiFormatTest extends \PHPUnit_Framework_TestCase
{

    public function provider_dateTime()
    {
        return [
            [new \DateTime('2014-05-05T12:30:22+01:00'), '2014-05-05T11:30:22Z'],
            [new \DateTime('2014-05-05'), '2014-05-05T00:00:00Z'],
            [null, null]
        ];
    }

    /**
     * @dataProvider provider_dateTime
     */
    public function test_dateTime($dateTime, $expected)
    {
        $this->assertEquals($expected, DateTimeApiFormat::dateTime($dateTime));
    }

    public function provider_date()
    {
        return [
            [new \DateTime('2014-02-11'), '2014-02-11'],
            [new \DateTime('2014-05-05'), '2014-05-05'],
            [null, null]
        ];
    }

    /**
     * @dataProvider provider_date
     */
    public function test_date($dateTime, $expected)
    {
        $this->assertEquals($expected, DateTimeApiFormat::date($dateTime));
    }
}
