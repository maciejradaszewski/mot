<?php

namespace DvsaCommon\Dto\Site;

use DvsaCommon\Date\Time;
use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;

/**
 * Class SiteTestingDailyScheduleDto
 *
 * @package DvsaCommon\Dto\Site
 */
class SiteTestingDailyScheduleDto extends AbstractDataTransferObject
{
    use CommonIdentityDtoTrait;

    /** @var  Time */
    private $openTime;
    /** @var  Time */
    private $closeTime;
    /** @var  integer */
    private $weekday;

    /**
     * @return Time
     */
    public function getOpenTime()
    {
        return $this->openTime;
    }

    /**
     * @param Time $openTime
     *
     * @return $this
     */
    public function setOpenTime($openTime)
    {
        $this->openTime = $openTime;
        return $this;
    }

    /**
     * @return Time
     */
    public function getCloseTime()
    {
        return $this->closeTime;
    }

    /**
     * @param Time $closeTime
     *
     * @return $this
     */
    public function setCloseTime($closeTime)
    {
        $this->closeTime = $closeTime;
        return $this;
    }

    public function getWeekday()
    {
        return $this->weekday;
    }

    /**
     * @return $this
     */
    public function setWeekday($weekday)
    {
        $this->weekday = (int)$weekday;
        return $this;
    }
}
