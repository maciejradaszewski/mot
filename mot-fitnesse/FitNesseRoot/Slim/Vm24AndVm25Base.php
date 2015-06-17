<?php

require_once 'configure_autoload.php';
use DvsaCommon\Enum\VehicleClassCode;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;
use DvsaCommon\Enum\ColourCode;

class Vm24AndVm25Base
{
    const ONE_TIME_PASSWORD_PASSING = '123456';

    public $username;
    public $password = TestShared::PASSWORD;
    private $vehicleId;
    protected $vehicleTestingStationId;

    private $primaryColour = ColourCode::BLACK;
    private $secondaryColour = ColourCode::NOT_STATED;
    private $vehicleClassCode = VehicleClassCode::CLASS_1;
    private $hasRegistration = 'true';
    private $result;

    public function success()
    {
        $helper = new MotTestHelper(new \MotFitnesse\Util\CredentialsProvider($this->username, $this->password));

        $postArray = [
            'vehicleId' => $this->vehicleId,
            'vehicleTestingStationId' => $this->vehicleTestingStationId,
            'primaryColour' => $this->primaryColour,
            'secondaryColour' => $this->secondaryColour,
            'hasRegistration' => $this->hasRegistration,
            'vehicleClassCode' => $this->vehicleClassCode,
            'fuelTypeId' => 'PE',
            'oneTimePassword' => self::ONE_TIME_PASSWORD_PASSING
        ];

        $this->result = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            $this,
            (new UrlBuilder())->motTest(),
            $postArray
        );

        $isSuccess = TestShared::resultIsSuccess($this->result);

        if ($isSuccess) {
            $motTestNumber = $this->result['data']['motTestNumber'];
            $helper->abortTest($motTestNumber);
        }

        return $isSuccess;
    }

    public function setSiteId($siteId)
    {
        $this->vehicleTestingStationId = $siteId;
    }

    public function setUserName($value)
    {
        $this->username = $value;
    }

    public function setVehicleId($value)
    {
        $this->vehicleId = $value;
    }

    public function setVehicleClassCode($value)
    {
        $this->vehicleClassCode = $value;
    }

    public function setInfoAboutVehicle($value)
    {
    }

    public function errorMessages()
    {
        return TestShared::errorMessages($this->result);
    }
}
