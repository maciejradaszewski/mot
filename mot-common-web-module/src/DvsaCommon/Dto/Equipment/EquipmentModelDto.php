<?php

namespace DvsaCommon\Dto\Equipment;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\VehicleClassification\VehicleClassDto;

class EquipmentModelDto extends AbstractDataTransferObject
{
    private $name;
    private $typeName;
    private $makeName;
    private $code;
    private $softwareVersion;
    private $certificationDate;
    private $equipmentIdentificationNumber;

    /** @var VehicleClassDto[] */
    private $vehicleClasses = [];

    /**
     * @var string
     * @see EquipmentModelStatusCode
     */
    private $status;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setCode($code)
    {
        $this->code = $code;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setMakeName($makeName)
    {
        $this->makeName = $makeName;
    }

    public function getMakeName()
    {
        return $this->makeName;
    }

    public function setTypeName($typeName)
    {
        $this->typeName = $typeName;
    }

    public function getTypeName()
    {
        return $this->typeName;
    }

    /**
     * @param string $status
     * @see EquipmentModelStatusCode
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
    /**
     *
     * @return string
     * @see EquipmentModelStatusCode
     */
    public function getStatus()
    {
        return $this->status;
    }

    public function setCertificationDate($certified)
    {
        $this->certificationDate = $certified;
    }

    public function getCertificationDate()
    {
        return $this->certificationDate;
    }

    public function setSoftwareVersion($softwareVersion)
    {
        $this->softwareVersion = $softwareVersion;
    }

    public function getSoftwareVersion()
    {
        return $this->softwareVersion;
    }

    public function setEquipmentIdentificationNumber($equipmentIdentificationNumber)
    {
        $this->equipmentIdentificationNumber = $equipmentIdentificationNumber;
    }

    public function getEquipmentIdentificationNumber()
    {
        return $this->equipmentIdentificationNumber;
    }

    public function setVehicleClasses($vehicleClasses)
    {
        $this->vehicleClasses = $vehicleClasses;
    }

    public function getVehicleClasses()
    {
        return $this->vehicleClasses;
    }
}
