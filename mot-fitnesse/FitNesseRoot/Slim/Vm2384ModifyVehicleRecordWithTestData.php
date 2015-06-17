<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\VehicleUrlBuilder;

class Vm2384ModifyVehicleRecordWithTestData
{
    const ONE_TIME_PASSWORD_PASSING = '123456';
    const ONE_TIME_PASSWORD_INVALID = '000000';

    protected $siteId;
    private $username;
    private $password = TestShared::PASSWORD;
    private $vehicleId;
    private $primaryColour;
    private $secondaryColour;
    private $vehicleClass;
    private $fuelType;
    private $hasRegistration = 'true';
    private $oneTimePassword;
    private $result;

    public function __construct($testerUsername, $siteId, $vehicleId)
    {
        $this->username = $testerUsername;
        $this->siteId = $siteId;
        $this->vehicleId = $vehicleId;
    }

    public function isVehicleDataUpdated()
    {
        $this->result = $this->executeTest();

        $vehicleData = $this->getVehicleData();

        $vehicle = $vehicleData['data'];

        if ($vehicle['colour']['code'] != $this->primaryColour ||
            $vehicle['colourSecondary']['code'] != $this->secondaryColour ||
            $vehicle['vehicleClass']['code'] != $this->vehicleClass ||
            $vehicle['fuelType']['code'] != $this->fuelType
        ) {
            return false;
        } else {
            return true;
        }
    }

    public function errors()
    {
        if (empty($this->result['errors'])) {
            return '';
        }

        return $this->result['errors'][0]['message'];
    }

    private function executeTest()
    {
        $input = [
            'vehicleId'               => $this->vehicleId,
            'vehicleTestingStationId' => $this->siteId,
            'primaryColour'           => $this->primaryColour,
            'secondaryColour'         => $this->secondaryColour,
            'vehicleClassCode'        => $this->vehicleClass,
            'fuelTypeId'              => $this->fuelType,
            'hasRegistration'         => $this->hasRegistration,
            'oneTimePassword'         => $this->oneTimePassword
        ];
        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            (new UrlBuilder())->mottest()->toString(),
            TestShared::METHOD_POST, $input, $this->username, $this->password
        );
        return TestShared::execCurlForJson($curlHandle);
    }

    private function getVehicleData()
    {
        $curlHandle = TestShared::prepareCurlHandleToSendJson(
            VehicleUrlBuilder::vehicle($this->vehicleId)->toString(),
            TestShared::METHOD_GET, null, $this->username, $this->password
        );

        return TestShared::execCurlForJson($curlHandle);
    }

    public function setPrimaryColour($value)
    {
        $this->primaryColour = $value;
    }

    public function setSecondaryColour($value)
    {
        $this->secondaryColour = $value;
    }

    public function setVehicleClass($value)
    {
        $this->vehicleClass = $value;
    }

    public function setFuelType($value)
    {
        $this->fuelType = $value;
    }

    public function setOneTimePassword($value)
    {
        $this->oneTimePassword = $this->decodeOtp($value);
    }

    private function decodeOtp($value)
    {
        switch ($value) {
            case 'NOT PROVIDED': return null;
            case 'VALID': return self::ONE_TIME_PASSWORD_PASSING;
            case 'INVALID': return self::ONE_TIME_PASSWORD_INVALID;
            default: return null;
        }
    }
}
