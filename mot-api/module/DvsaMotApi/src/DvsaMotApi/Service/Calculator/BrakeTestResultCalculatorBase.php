<?php
namespace DvsaMotApi\Service\Calculator;

/**
 * Class BrakeTestResultCalculatorBase
 *
 * @package DvsaMotApi\Service\Calculator
 */
abstract class BrakeTestResultCalculatorBase
{
    protected function calculatePercentLocked($potentialLocks)
    {
        $locksCount = count($potentialLocks);
        if ($locksCount === 0) {
            return 0;
        }
        $numberOfLocks = 0;
        foreach ($potentialLocks as $lock) {
            if ($lock === true) {
                $numberOfLocks += 1;
            }
        }
        return round(100 * $numberOfLocks / $locksCount);
    }

    protected function calculateEfficiency($effort, $weight)
    {
        if (!$weight) {
            return 0;
        }

        $result = $effort / $weight;
        $result *= 100;
        $result = round($result, 3);
        return floor($result);
    }
}
