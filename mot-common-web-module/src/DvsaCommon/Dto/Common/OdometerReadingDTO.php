<?php

namespace DvsaCommon\Dto\Common;

use DvsaCommon\Dto\AbstractDataTransferObject;

/**
 * Class OdometerReadingDTO
 *
 * @package DvsaMotApi\Dto
 */
class OdometerReadingDTO extends AbstractDataTransferObject
{
    /**
     * @var integer $value
     */
    private $value;

    /**
     * @var string $unit
     */
    private $unit;

    /**
     * @var string  $resultType
     */
    private $resultType;

    /** @var string */
    private $issuedDate;

    /**
     * @return OdometerReadingDTO
     */
    public static function create()
    {
        return new OdometerReadingDTO();
    }
    /**
     * @param $resultType
     *
     * @return OdometerReadingDTO
     */
    public function setResultType($resultType)
    {
        $this->resultType = $resultType;
        return $this;
    }

    /**
     * @return string
     */
    public function getResultType()
    {
        return $this->resultType;
    }

    /**
     * @param $unit
     *
     * @return OdometerReadingDTO
     */
    public function setUnit($unit)
    {
        $this->unit = $unit;
        return $this;
    }

    /**
     * @return string
     */
    public function getUnit()
    {
        return $this->unit;
    }

    /**
     * @param $value
     *
     * @return OdometerReadingDTO
     */
    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function getIssuedDate()
    {
        return $this->issuedDate;
    }

    /**
     * @param string $issuedDate
     *
     * @return OdometerReadingDTO
     */
    public function setIssuedDate($issuedDate)
    {
        $this->issuedDate = $issuedDate;

        return $this;
    }
}
