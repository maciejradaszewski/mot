<?php

namespace DvsaCommon\Dto\Vehicle;

/**
 * Dto Class for DvlaVehicle
 *
 * @package DvsaCommon\Dto\Vehicle
 */
class DvlaVehicleDto extends AbstractVehicleDto
{
    /** @var int */
    private $designedGrossWeight;
    /** @var int */
    private $unladenWeight;
    /** @var string */
    private $makeInFull;

    /**
     * @param int $designedGrossWeight
     */
    public function setDesignedGrossWeight($designedGrossWeight)
    {
        $this->designedGrossWeight = $designedGrossWeight;

        return $this;
    }

    /**
     * @return int
     */
    public function getDesignedGrossWeight()
    {
        return $this->designedGrossWeight;
    }

    /**
     * @param int $unladenWeight
     */
    public function setUnladenWeight($unladenWeight)
    {
        $this->unladenWeight = $unladenWeight;

        return $this;
    }

    /**
     * @return int
     */
    public function getUnladenWeight()
    {
        return $this->unladenWeight;
    }

    /**
     * @param string $makeInFull
     */
    public function setMakeInFull($makeInFull)
    {
        $this->makeInFull = $makeInFull;

        return $this;
    }

    /**
     * @return string
     */
    public function getMakeInFull()
    {
        return $this->makeInFull;
    }

    /**
     * {@inheritdoc}
     */
    public function isDvla()
    {
        return true;
    }
}
