<?php

use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Date\DateUtils;

class DateIntervalsForOtherTests
{
    private $daysInterval = 0;
    private $monthsInterval = 0;
    private $yearsInterval = 0;
    private $isInFuture;

    private $forMotExpiry;

    public function setDaysInterval($daysInterval)
    {
        if($daysInterval !== '') {
            $this->daysInterval = $daysInterval;
        }else{
            $this->daysInterval = 0;
        }
    }

    public function setMonthsInterval($monthsInterval)
    {
        if($monthsInterval !== '') {
            $this->monthsInterval = $monthsInterval;
        }else{
            $this->monthsInterval = 0;
        }
    }

    public function setYearsInterval($yearsInterval)
    {
        if($yearsInterval !== '') {
            $this->yearsInterval = $yearsInterval;
        } else {
            $this->yearsInterval = 0;
        }
    }

    public function setInFuture($isInFuture)
    {
        $this->isInFuture = $isInFuture === 'true';
    }

    public function setForMotExpiry($forMotExpiry)
    {
        $this->forMotExpiry = $forMotExpiry === 'true';
    }

    public function dateVariable()
    {
        $dateInterval = new DateInterval('P' . $this->yearsInterval . 'Y' .  $this->monthsInterval . 'M'
    . $this->daysInterval . 'D');

        if ($this->isInFuture) {
            $date = DateUtils::today()->add($dateInterval);
        } else {
            $date = DateUtils::today()->sub($dateInterval);
        }

        if($this->forMotExpiry){
            $date = $date->sub(new DateInterval('P1D'));
        }
        return DateTimeApiFormat::date($date);
    }
}
