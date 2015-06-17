<?php

namespace DvsaCommon\Dto\BrakeTest;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\BrakeTest\BrakeTestConfigurationDtoInterface as DtoInterface;

/**
 * Dto for transferring class 1 and 2 brake configurations
 */
class BrakeTestConfigurationClass1And2Dto extends AbstractDataTransferObject implements DtoInterface
{
    protected $brakeTestType;
    protected $vehicleWeightFront;
    protected $vehicleWeightRear;
    protected $riderWeight;
    protected $sidecarWeight;
    protected $isSidecarAttached;

    /**
     * @param string $brakeTestType
     *
     * @return $this
     */
    public function setBrakeTestType($brakeTestType)
    {
        $this->brakeTestType = $brakeTestType;
        return $this;
    }

    /**
     * @return string
     */
    public function getBrakeTestType()
    {
        return $this->brakeTestType;
    }

    /**
     * @param bool $isSidecarAttached
     *
     * @return $this
     */
    public function setIsSidecarAttached($isSidecarAttached)
    {
        $this->isSidecarAttached = $isSidecarAttached;
        return $this;
    }

    /**
     * @return bool
     */
    public function getIsSidecarAttached()
    {
        return $this->isSidecarAttached;
    }

    /**
     * @param string $riderWeight
     *
     * @return $this
     */
    public function setRiderWeight($riderWeight)
    {
        $this->riderWeight = $riderWeight;
        return $this;
    }

    /**
     * @return string
     */
    public function getRiderWeight()
    {
        return $this->riderWeight;
    }

    /**
     * @param string $sidecarWeight
     *
     * @return $this
     */
    public function setSidecarWeight($sidecarWeight)
    {
        $this->sidecarWeight = $sidecarWeight;
        return $this;
    }

    /**
     * @return string
     */
    public function getSidecarWeight()
    {
        return $this->sidecarWeight;
    }

    /**
     * @param string $vehicleWeightFront
     *
     * @return $this
     */
    public function setVehicleWeightFront($vehicleWeightFront)
    {
        $this->vehicleWeightFront = $vehicleWeightFront;
        return $this;
    }

    /**
     * @return string
     */
    public function getVehicleWeightFront()
    {
        return $this->vehicleWeightFront;
    }

    /**
     * @param string $vehicleWeightRear
     *
     * @return $this
     */
    public function setVehicleWeightRear($vehicleWeightRear)
    {
        $this->vehicleWeightRear = $vehicleWeightRear;
        return $this;
    }

    /**
     * @return string
     */
    public function getVehicleWeightRear()
    {
        return $this->vehicleWeightRear;
    }
}
