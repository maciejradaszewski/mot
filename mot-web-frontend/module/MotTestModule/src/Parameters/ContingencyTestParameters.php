<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\MotTestModule\Parameters;

use Zend\Stdlib\Parameters;

/**
 * ContingencyTestParameters is a container for POST data.
 */
class ContingencyTestParameters extends Parameters
{
    /**
     * ContingencyTestParameters constructor.
     *
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        parent::__construct($this->addLeadingZeros($values));
    }

    /**
     * Populate from native PHP array.
     *
     * @param array $values
     */
    public function fromArray(array $values)
    {
        $this->exchangeArray($this->addLeadingZeros($values));
    }

    /**
     * Allow the omission of leading zeros.
     *
     * @param array $data
     *
     * @return array
     */
    private function addLeadingZeros(array $data)
    {
        foreach (['performed_at_month', 'performed_at_day', 'performed_at_minute'] as $k) {
            if (isset($data[$k]) && !empty($data[$k]) && is_numeric($data[$k])) {
                $data[$k] = sprintf('%02d', $data[$k]);
            }
        }

        return $data;
    }
}
