<?php

require_once 'configure_autoload.php';
use DvsaCommon\Enum\MotTestStatusName;
use MotFitnesse\Util\TestShared;

class Vm2728OneActiveTestPerVehicleTestCreation
{
    private $username;
    private $password = TestShared::PASSWORD;
    private $vehicleId;
    private $finishTest;
    private $siteId = 1;

    public function errorMessage()
    {
        $motHelper = new MotTestHelper(new \MotFitnesse\Util\CredentialsProvider($this->username, $this->password));

        try {
            $motTestData = $motHelper->createMotTest($this->vehicleId, null, $this->siteId);

            $motTestNumber = $motTestData['motTestNumber'];
            $motHelper->odometerUpdate($motTestNumber);
            $motHelper->passBrakeTestResults($motTestNumber);

            if ($this->finishTest) {
                $motHelper->changeStatus($motTestNumber, MotTestStatusName::PASSED);
            }

            return '';

        } catch (ApiErrorException $ex) {
            return $ex->getMessage();
        }
    }

    public function setUsername($value)
    {
        $this->username = $value;
    }

    public function setSiteId($v)
    {
        $this->siteId = $v;
    }

    public function setVehicleId($value)
    {
        $this->vehicleId = $value;
    }

    public function setFinishTest($value)
    {
        $this->finishTest = filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    public function setInfoAboutResult($value)
    {
    }
}
