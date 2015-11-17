<?php

namespace DvsaCommonTest\DtoSerialization\TestDto;

use DvsaCommon\Date\Time;
use DvsaCommon\DtoSerialization\ReflectiveDtoInterface;

class SampleDtoWithConvertible implements ReflectiveDtoInterface
{
    private $date;

    /** @var Time[] */
    private $times;

    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getTimes()
    {
        return $this->times;
    }

    /**
     * @param \DvsaCommon\Date\Time[] $times
     */
    public function setTimes(array $times = null)
    {
        $this->times = $times;
    }
}
