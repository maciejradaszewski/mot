<?php

use MotFitnesse\Util\TestShared;
use DvsaCommon\Enum\ColourCode;
use DvsaCommon\Enum\FuelTypeCode;
use DvsaCommon\Enum\VehicleClassCode;
use MotFitnesse\Util\UrlBuilder;

class Vm4497CreateVehicle
{
    private $login;
    private $error;
    private $vtsId;

    public function __construct($vtsId)
    {
        $this->vtsId = $vtsId;
    }

    public function setLogin($value)
    {
        $this->login = $value;
    }

    public function success()
    {
        $this->error = null;
        $client = FitMotApiClient::create($this->login, TestShared::PASSWORD);

        try {
            $client->post((new UrlBuilder())->vehicle(), $this->generateVehicleData());
            return true;
        } catch (\Exception $e) {
            $this->error = $e->getMessage();
            return false;
        }
    }

    public function error()
    {
        return $this->error;
    }

    public function setUserInfo()
    {
    }

    public function generateVehicleData()
    {
        $vrm = 'FIT090293';
        $vin = 'FITVINNUMBER21323';

        $data = [
            'registrationNumber'    => $vrm,
            'bodyType'              => '12',
            'vin'                   => $vin,
            'make'                  => '18811',
            'makeOther'             => '',
            'model'                 => '01459',
            'modelOther'            => '',
            'modelType'             => '',
            'colour'                => ColourCode::ORANGE,
            'secondaryColour'       => ColourCode::BLACK,
            'dateOfFirstUse'        => '1999-01-01',
            'fuelType'              => FuelTypeCode::PETROL,
            'testClass'             => VehicleClassCode::CLASS_4,
            'countryOfRegistration' => 4,
            'cylinderCapacity'      => 1234,
            'transmissionType'      => 1,
            'oneTimePassword'       => '123456',
            'returnOriginalId'      => 'true',
            'vtsId'                 => $this->vtsId
        ];

        return $data;
    }
}
