<?php

namespace DvsaClient\ViewModel;

use DvsaCommon\Date\DateUtils;

class DateViewModel
{
    /** @var string */
    private $day = 1;
    /** @var string */
    private $month = 1;
    /** @var string */
    private $year;

    public function __construct($year = null, $month = null, $day = null)
    {
        $this->setYear($year);
        $this->setMonth($year && $month === null ? $this->month : $month);
        $this->setDay($year && $day === null ? $this->day : $day);
    }

    /**
     * @param \DateTime $date
     *
     * @return $this
     */
    public function setDate($date)
    {
        if ($date instanceof \DateTime) {
            $this->setYear($date->format('Y'));
            $this->setMonth($date->format('n'));
            $this->setDay($date->format('j'));
        }

        return $this;
    }

    /**
     * Returns correct date or null
     *
     * @return \DateTime|null
     */
    public function getDate()
    {
        //  all check already made inside DateUtils library, so no reason to duplicate.
        //  this function return null in case of invalid date, so exception should be suppressed
        try {
            return DateUtils::toDateFromParts($this->getDay(), $this->getMonth(), $this->getYear());
        } catch (\Exception $e) {
        }

        return null;
    }

    /**
     * @return string
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param string $day
     *
     * @return DateViewModel
     */
    public function setDay($day)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * @return string
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @param string $month
     *
     * @return DateViewModel
     */
    public function setMonth($month)
    {
        $this->month = $month;

        return $this;
    }

    /**
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }


    /**
     * @param string $year
     *
     * @return DateViewModel
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }
}
