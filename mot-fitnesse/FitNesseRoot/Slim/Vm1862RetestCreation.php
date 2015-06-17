<?php

require_once 'configure_autoload.php';
use DvsaCommon\Enum\VehicleClassCode;
use MotFitnesse\Util\TestShared;

class Vm1862RetestCreation
{
    protected $vehicleTestingStationId = '1';
    private $username;
    private $password = TestShared::PASSWORD;
    private $vehicleId;
    private $primaryColour;
    private $secondaryColour;
    private $hasRegistration = 'true';

    const INSIGNIFICANT_COLOUR = "B";

    public function __construct($username = null)
    {
        $this->username = $username;
    }

    public function errorMessage()
    {
        $motHelper = new MotTestHelper(new \MotFitnesse\Util\CredentialsProvider($this->username, $this->password));
        try {
            $motTestData = $motHelper->createMotTest(
                $this->vehicleId,
                null,
                $this->vehicleTestingStationId,
                $this->primaryColour === "null" ? null : self::INSIGNIFICANT_COLOUR,
                $this->secondaryColour === "null" ? null : self::INSIGNIFICANT_COLOUR,
                $this->hasRegistration,
                VehicleClassCode::CLASS_4,
                'PE',
                MotTestHelper::TYPE_MOT_TEST_RETEST
            );

            $motTestNumber = $motTestData['motTestNumber'];
            $motHelper->odometerUpdate($motTestNumber);
            $motHelper->passBrakeTestResults($motTestNumber);
            $motHelper->changeStatus($motTestNumber, "PASSED");

        } catch (ApiErrorException $ex) {
            return $ex->getDisplayMessage();
        }
        return '';
    }

    public function setUsername($value)
    {
        $this->username = $value;
    }

    public function setVehicleId($value)
    {
        $this->vehicleId = $value;
    }

    public function setVehicleTestingStationId($value)
    {
        $this->vehicleTestingStationId = $value;
    }

    public function setPrimaryColour($value)
    {
        $this->primaryColour = $value;
    }

    public function setSecondaryColour($value)
    {
        $this->secondaryColour = $value;
    }

    public function setHasRegistration($value)
    {
        $this->hasRegistration = $value;
    }

    public function setInfoAboutResult($value)
    {
    }
}
