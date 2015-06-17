<?php

namespace DvsaCommonTest\Date;

use DvsaCommon\Date\DateTimeHolder;

/**
 * Test implementation for DateTimeHolder
 *
 * Class TestDateTimeHolder
 *
 * @package DvsaCommonTest\Date
 */
class TestDateTimeHolder extends DateTimeHolder
{

    private $currentDateTime;

    /**
     * @param \DateTime $currentDateTime
     */
    public function __construct($currentDateTime)
    {
        $this->setCurrent($currentDateTime);
    }

    /**
     * Sets the current date in the holder
     *
     * @param \DateTime $currentDateTime
     */
    public function setCurrent(\DateTime $currentDateTime)
    {
        $this->currentDateTime = $currentDateTime;
    }

    public function getCurrent($withMilliseconds = false)
    {
        return $this->currentDateTime;
    }

    public function getCurrentDate()
    {
        return $this->currentDateTime;
    }
}
