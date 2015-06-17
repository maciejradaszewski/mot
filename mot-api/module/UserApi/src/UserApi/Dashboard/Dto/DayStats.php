<?php

namespace UserApi\Dashboard\Dto;

/**
 * All the tester's stats for the day
 */
class DayStats
{

    private $total;
    private $numberOfPasses;
    private $numberOfFails;
    private $numberOfRetests;

    public function __construct(
        $total,
        $numberOfPasses,
        $numberOfFails,
        $numberOfRetests
    ) {
        $this->total = $total;
        $this->numberOfPasses = $numberOfPasses;
        $this->numberOfFails = $numberOfFails;
        $this->numberOfRetests = $numberOfRetests;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'total'           => $this->total,
            'numberOfPasses'  => $this->numberOfPasses,
            'numberOfFails'   => $this->numberOfFails,
            'numberOfRetests' => $this->numberOfRetests,
        ];
    }
}
