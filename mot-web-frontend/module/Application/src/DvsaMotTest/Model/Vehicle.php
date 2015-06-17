<?php

namespace DvsaMotTest\Model;

use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Date\DateUtils;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Class Vehicle
 */
class Vehicle
{
    private $registrationNumber;
    private $vin;
    private $make;
    private $model;
    private $makeOther;
    private $modelOther;
    private $modelType;
    private $colour;
    private $secondaryColour;
    private $dateOfFirstUse;
    private $fuelType;
    private $testClass;
    private $countryOfRegistration;
    private $cylinderCapacity;
    private $transmissionType;
    private $emptyVrmReason;
    private $emptyVinReason;

    public function __construct()
    {
        $this->countryOfRegistration = 1;
    }

    public function populate($data)
    {
        $hydrator = new ClassMethods();
        $hydrator->hydrate($data, $this);

        $this->dateOfFirstUse = DateTimeApiFormat::date(
            DateUtils::toDateFromParts(
                $data['dateOfFirstUse']['day'],
                $data['dateOfFirstUse']['month'],
                $data['dateOfFirstUse']['year']
            )
        );
    }

    public function toArray()
    {
        $hydrator = new ClassMethods(false);

        return $hydrator->extract($this);
    }

    public function getColour()
    {
        return $this->colour;
    }

    public function getCountryOfRegistration()
    {
        return $this->countryOfRegistration;
    }

    public function getCylinderCapacity()
    {
        return $this->cylinderCapacity;
    }

    public function getFuelType()
    {
        return $this->fuelType;
    }

    public function getMake()
    {
        return $this->make;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function getModelType()
    {
        return $this->modelType;
    }

    public function getRegistrationNumber()
    {
        return $this->registrationNumber;
    }

    public function getSecondaryColour()
    {
        return $this->secondaryColour;
    }

    public function getTestClass()
    {
        return $this->testClass;
    }

    public function getTransmissionType()
    {
        return $this->transmissionType;
    }

    public function getVin()
    {
        return $this->vin;
    }

    public function getDateOfFirstUse()
    {
        return $this->dateOfFirstUse;
    }

    public function setColour($colour)
    {
        $this->colour = $colour;
    }

    public function setCountryOfRegistration($countryOfRegistration)
    {
        $this->countryOfRegistration = $countryOfRegistration;
    }

    public function setCylinderCapacity($cylinderCapacity)
    {
        $this->cylinderCapacity = $cylinderCapacity;
    }

    public function setDateOfFirstUse($dateOfFirstUse)
    {
        $this->dateOfFirstUse = $dateOfFirstUse;
    }

    public function setFuelType($fuelType)
    {
        $this->fuelType = $fuelType;
    }

    public function setMake($make)
    {
        $this->make = $make;
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function setModelType($modelType)
    {
        $this->modelType = $modelType;
    }

    public function setRegistrationNumber($registrationNumber)
    {
        $this->registrationNumber = $registrationNumber;
    }

    public function setSecondaryColour($secondaryColour)
    {
        $this->secondaryColour = $secondaryColour;
    }

    public function setTestClass($testClass)
    {
        $this->testClass = $testClass;
    }

    public function setTransmissionType($transmissionType)
    {
        $this->transmissionType = $transmissionType;
    }

    public function setVin($vin)
    {
        $this->vin = $vin;
    }

    /**
     * @param mixed $makeOther
     */
    public function setMakeOther($makeOther)
    {
        $this->makeOther = $makeOther;
    }

    /**
     * @return mixed
     */
    public function getMakeOther()
    {
        return $this->makeOther;
    }

    /**
     * @param mixed $modelOther
     */
    public function setModelOther($modelOther)
    {
        $this->modelOther = $modelOther;
    }

    /**
     * @return mixed
     */
    public function getModelOther()
    {
        return $this->modelOther;
    }

    /**
     * @param int $reason
     * @return $this
     */
    public function setEmptyVrmReason($reasonId)
    {
        $this->emptyVrmReason = $reasonId;
        return $this;
    }

    /**
     * @return int
     */
    public function getEmptyVrmReason()
    {
        return $this->emptyVrmReason;
    }

    /**
     * @param int $reason
     * @return $this
     */
    public function setEmptyVinReason($reasonId)
    {
        $this->emptyVinReason = $reasonId;
        return $this;
    }

    /**
     * @return int
     */
    public function getEmptyVinReason()
    {
        return $this->emptyVinReason;
    }

}
