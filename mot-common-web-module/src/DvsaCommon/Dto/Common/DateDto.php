<?php

namespace DvsaCommon\Dto\Common;

use DvsaCommon\Date\DateUtils;
use DvsaCommon\Date\Exception\IncorrectDateFormatException;
use DvsaCommon\Date\Exception\NonexistentDateException;
use DvsaCommon\Dto\AbstractDataTransferObject;

/**
 * Class DateDto
 * @package DvsaCommon\Dto\Common
 */
class DateDto extends AbstractDataTransferObject
{
    /** @var string $day */
    private $day;
    /** @var string $month */
    private $month;
    /** @var string $year */
    private $year;

    /**
     * @param string $year
     * @param string $month
     * @param string $day
     */
    public function __construct($year = null, $month = null, $day = null)
    {
        $this->setYear($year);
        $this->setMonth($month);
        $this->setDay($day);
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
     * @return \DateTime
     */
    public function getDate()
    {
        try {
            return DateUtils::toDateFromParts($this->getDay(), $this->getMonth(), $this->getYear());
        } catch (IncorrectDateFormatException $e) {
            return null;
        } catch (NonexistentDateException $e) {
            return null;
        }
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
     * @return $this
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
     * @return $this
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
     * @return $this
     */
    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }
}
