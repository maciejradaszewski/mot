<?php

namespace DvsaCommon\Dto\Site;

use DvsaCommon\Dto\AbstractDataTransferObject;

/**
 * Class MotTestLogDto.
 */
class MotTestLogSummaryDto extends AbstractDataTransferObject
{
    /**
     * @var
     */
    private $year;

    /**
     * @var
     */
    private $month;

    /**
     * @var
     */
    private $week;

    /**
     * @var
     */
    private $day;

    /**
     * @return mixed
     */
    public function getToday()
    {
        return $this->day;
    }

    /**
     * @param mixed $day
     *
     * @return MotTestLogSummaryDto
     */
    public function setToday($day)
    {
        $this->day = (int) $day;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param mixed $month
     *
     * @return MotTestLogSummaryDto
     */
    public function setMonth($month)
    {
        $this->month = (int) $month;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getWeek()
    {
        return $this->week;
    }

    /**
     * @param mixed $week
     *
     * @return MotTestLogSummaryDto
     */
    public function setWeek($week)
    {
        $this->week = (int) $week;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param mixed $year
     *
     * @return MotTestLogSummaryDto
     */
    public function setYear($year)
    {
        $this->year = (int) $year;

        return $this;
    }
}
