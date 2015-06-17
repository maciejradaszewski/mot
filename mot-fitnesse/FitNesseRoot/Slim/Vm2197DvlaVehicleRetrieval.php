<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\VehicleUrlBuilder;

class Vm2197DvlaVehicleRetrieval
{
    public $username = 'tester1';
    public $password = TestShared::PASSWORD;
    private $resultVehicle;

    public function setId($id)
    {
        $this->doQuery($id);
    }
    public function registration()
    {
        return $this->resultVehicle['registration'];
    }

    public function cylinderCapacity()
    {
        return $this->resultVehicle['cylinderCapacity'];
    }

    public function make()
    {
        return $this->resultVehicle['makeName'];
    }

    public function model()
    {
        return $this->resultVehicle['modelName'];
    }

    public function primaryColour()
    {
        return $this->resultVehicle['colour']['name'];
    }

    public function secondaryColour()
    {
        return $this->resultVehicle['colourSecondary']['name'];
    }
    public function fuelType()
    {
        return $this->resultVehicle['fuelType']['name'];
    }

    public function bodyType()
    {
        return $this->resultVehicle['bodyType']['name'];
    }

    public function firstUsedDate()
    {
        return date("j F Y", strtotime($this->resultVehicle['firstUsedDate']));
    }


    public function vin()
    {
        return $this->resultVehicle['vin'];
    }

    protected function doQuery($id)
    {
        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            VehicleUrlBuilder::dvlaVehicle($id)->toString(),
            TestShared::METHOD_GET,
            null,
            $this->username,
            $this->password
        );

        $result = TestShared::execCurlForJson($curlHandle);
        $this->resultVehicle = $result['data'];
    }
}
