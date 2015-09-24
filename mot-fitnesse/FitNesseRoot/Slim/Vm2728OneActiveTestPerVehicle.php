<?php

require_once 'configure_autoload.php';

use DvsaCommon\Enum\MotTestTypeCode;
use DvsaCommon\Enum\VehicleClassCode;
use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\VehicleUrlBuilder;
use DvsaCommon\Enum\ColourCode;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\FuelTypeCode;
use MotFitnesse\Util\CredentialsProvider;

class Vm2728OneActiveTestPerVehicle
{
    private $username;
    private $siteId = 1;

    private $startNewTest;
    private $previousTest;
    private $finishTest;
    private $vehicles = [];
    private $currentVehicle;

    public function inProgress()
    {
        $apiClient = FitMotApiClient::createForCreds($this->getCredentialsProvider());
        if (!isset($this->vehicles[$this->currentVehicle])) {
            $vth = new VehicleTestHelper($apiClient);
            $this->vehicles[$this->currentVehicle] = $vth->generateVehicle();
        }
        $currentVehicleId = $this->vehicles[$this->currentVehicle];

        $mth = new MotTestHelper($this->getCredentialsProvider());

        if ($this->startNewTest) {

            $mth->createMotTest(
                $currentVehicleId,
                null,
                $this->siteId,
                ColourCode::ORANGE,
                ColourCode::BLACK,
                true,
                VehicleClassCode::CLASS_4,
                FuelTypeCode::PETROL,
                'NORMAL',
                MotTestTypeCode::DEMONSTRATION_TEST_FOLLOWING_TRAINING
            );

            $this->previousTest = $mth->createMotTest($currentVehicleId, null, $this->siteId)['motTestNumber'];
            $mth->odometerUpdate($this->previousTest);
            $mth->passBrakeTestResults($this->previousTest);
        }

        if ($this->finishTest) {
            $mth = new MotTestHelper($this->getCredentialsProvider());
            $mth->changeStatus($this->previousTest, MotTestStatusName::PASSED);
        }

        $isMotTestInProgress = $apiClient->get(VehicleUrlBuilder::vehicle($currentVehicleId)->testInProgressCheck());

        return $isMotTestInProgress;
    }

    public function setTesterUsername($v)
    {
        $this->username = $v;
    }

    public function setSiteId($v)
    {
        $this->siteId = $v;
    }

    public function setUsername($v)
    {
        $this->username = $v;
    }

    public function setFinishTest($v)
    {
        $this->finishTest = filter_var($v, FILTER_VALIDATE_BOOLEAN);
    }

    public function setStartNewTest($v)
    {
        $this->startNewTest = filter_var($v, FILTER_VALIDATE_BOOLEAN);
    }

    public function setInfoAboutResult($v)
    {
    }

    public function setVehicle($v)
    {
        $this->currentVehicle = $v;
    }

    private function getCredentialsProvider()
    {
        return new CredentialsProvider($this->username, TestShared::PASSWORD);
    }
}
