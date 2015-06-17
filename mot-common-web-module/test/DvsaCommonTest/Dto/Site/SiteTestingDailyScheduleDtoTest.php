<?php

namespace DvsaCommonTest\Dto\Site;

use DvsaCommon\Dto\Site\SiteTestingDailyScheduleDto;
use DvsaCommonTest\Dto\AbstractDtoTester;

/**
 * Unit test for SiteTestingDailyScheduleDto class
 *
 * @package DvsaCommonTest\Dto\Equipment
 */
class SiteTestingDailyScheduleDtoTest extends AbstractDtoTester
{
    protected $dtoClassName = SiteTestingDailyScheduleDto::class;

    public function providerGettersAndSetters()
    {
        return
            [
                'Weekday' => ['Weekday', 99999999],
            ] +
            parent::providerGettersAndSetters();
    }
}
