<?php
namespace DvsaCommon\Dto\Vehicle;

use DvsaCommon\Dto\AbstractDataTransferObject;

class VehicleCreatedDto extends AbstractDataTransferObject
{
    private $vehicleId;

    private $startedMotTestNumber;

    public function setVehicleId($vehicleId)
    {
        $this->vehicleId = $vehicleId;
    }

    public function getVehicleId()
    {
        return $this->vehicleId;
    }

    public function setStartedMotTestNumber($startedMotTestId)
    {
        $this->startedMotTestNumber = $startedMotTestId;
    }

    public function getStartedMotTestNumber()
    {
        return $this->startedMotTestNumber;
    }
}
