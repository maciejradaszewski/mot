<?php

namespace DvsaCommon\Dto\MotTesting;

use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Utility\TypeCheck;

class MotTestOptionsDto
{
    private $motTestStartedDate;
    private $vehicleMake;
    private $vehicleModel;
    private $vehicleRegistrationNumber;

    /**
     * @param array $data
     *
     * @return \DvsaCommon\Dto\MotTesting\MotTestOptionsDto
     */
    public static function fromArray($data)
    {
        TypeCheck::assertArray($data);

        $vehicle = ArrayUtils::get($data, 'vehicle');

        $dto = (new MotTestOptionsDto())
            ->setMotTestStartedDate(ArrayUtils::get($data, 'startedDate'))
            ->setVehicleMake(ArrayUtils::get($vehicle, 'make'))
            ->setVehicleModel(ArrayUtils::get($vehicle, 'model'))
            ->setVehicleRegistrationNumber(ArrayUtils::get($vehicle, 'vehicleRegistrationNumber'));

        return $dto;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'startedDate' => $this->motTestStartedDate,
            'vehicle'     => [
                'make'                      => $this->vehicleMake,
                'model'                     => $this->vehicleModel,
                'vehicleRegistrationNumber' => $this->vehicleRegistrationNumber
            ]
        ];
    }

    /**
     * @return string
     */
    public function getVehicleMake()
    {
        return $this->vehicleMake;
    }

    /**
     * @return string
     */
    public function getVehicleModel()
    {
        return $this->vehicleModel;
    }

    /**
     * @return string
     */
    public function getVehicleRegistrationNumber()
    {
        return $this->vehicleRegistrationNumber;
    }

    /**
     * @return string
     */
    public function getMotTestStartedDate()
    {
        return $this->motTestStartedDate;
    }

    /**
     * @param $motTestStartedDate
     *
     * @return MotTestOptionsDto
     */
    public function setMotTestStartedDate($motTestStartedDate)
    {
        $this->motTestStartedDate = $motTestStartedDate;

        return $this;
    }

    /**
     * @param $vehicleMake
     *
     * @return MotTestOptionsDto
     */
    public function setVehicleMake($vehicleMake)
    {
        $this->vehicleMake = $vehicleMake;

        return $this;
    }

    /**
     * @param $vehicleModel
     *
     * @return MotTestOptionsDto
     */
    public function setVehicleModel($vehicleModel)
    {
        $this->vehicleModel = $vehicleModel;

        return $this;
    }

    /**
     * @param string $vehicleRegistrationNumber
     *
     * @return MotTestOptionsDto
     */
    public function setVehicleRegistrationNumber($vehicleRegistrationNumber)
    {
        $this->vehicleRegistrationNumber = $vehicleRegistrationNumber;

        return $this;
    }
}
