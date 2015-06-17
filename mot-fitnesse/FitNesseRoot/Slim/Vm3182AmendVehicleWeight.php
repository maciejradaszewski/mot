<?php

require_once 'configure_autoload.php';

use DvsaCommon\Enum\MotTestStatusName;
use MotFitnesse\Util\CredentialsProvider;
use MotFitnesse\Util\UrlBuilder;
use DvsaCommon\Enum\ColourCode;
use DvsaCommon\Enum\BrakeTestTypeCode;
use DvsaCommon\Enum\WeightSourceCode;
use DvsaCommon\Enum\ReasonForCancelId;

class Vm3182AmendVehicleWeight
{
    const RFR_FAILURE_ID = 838;

    private $username;
    private $siteId = 1;
    private $vehicleId;

    private $brakeTestVehicleWeight;
    private $brakeTestWeightType;
    private $testStatus;
    private $vtrVehicleWeight;

    private $brakeTestTypeNameToCodeMap = [
        'vsi' => WeightSourceCode::VSI,
        'presented' => WeightSourceCode::PRESENTED,
    ];

    public function setTesterUsername($v)
    {
        $this->username = $v;
    }

    public function setVehicleId($v)
    {
        $this->vehicleId = $v;
    }

    public function setSiteId($v)
    {
        $this->siteId = $v;
    }

    public function setBrakeTestVehicleWeight($vehicleWeight)
    {
        $this->brakeTestVehicleWeight = $vehicleWeight;
    }

    public function setBrakeTestWeightType($weightType)
    {
        $this->brakeTestWeightType = $this->brakeTestTypeNameToCodeMap[$weightType];
    }

    public function setTestStatus($status)
    {
        $this->testStatus = $status;
        $this->execute();
    }

    public function vtrVehicleWeight()
    {
        return $this->vtrVehicleWeight;
    }

    private function execute()
    {
        $credentialsProvider = new CredentialsProvider($this->username, \MotFitnesse\Util\TestShared::PASSWORD);
        $motTestHelper = new MotTestHelper($credentialsProvider);
        //All that matters is that vehicle is of class 3 or 4.
        $motTestCreateResult = $motTestHelper->createMotTest(
            $this->vehicleId,
            null,
            $this->siteId,
            ColourCode::WHITE,
            ColourCode::WHITE,
            false
        );
        $motTestNumber = $motTestCreateResult['motTestNumber'];
        $this->updateBrakeTestResult($motTestNumber, $credentialsProvider);
        $motTestHelper->odometerUpdate($motTestNumber);
        if ($this->testStatus === MotTestStatusName::FAILED) {
            $motTestHelper->addRfr($motTestNumber, self::RFR_FAILURE_ID);
        }
        if ($this->testStatus === MotTestStatusName::ABORTED) {
            $motTestHelper->changeStatus($motTestNumber, $this->testStatus, "123456", ReasonForCancelId::ABORT);
        } else {
            $motTestHelper->changeStatus($motTestNumber, $this->testStatus);
        }
        $motTest = $motTestHelper->getMotTest($motTestNumber);
        $this->vtrVehicleWeight = $motTest['vehicle']['weight'];
    }

    private function updateBrakeTestResult($motTestNumber, $credentialsProvider)
    {
        $data = [
            'vehicleWeight'              => $this->brakeTestVehicleWeight,
            'weightType'                 => $this->brakeTestWeightType,
            'serviceBrake1TestType'      => BrakeTestTypeCode::DECELEROMETER,
            'serviceBrake1Efficiency'    => 80,
            'parkingBrakeTestType'       => BrakeTestTypeCode::ROLLER,
            'parkingBrakeEffortNearside' => 90,
            'parkingBrakeEffortOffside'  => 90,
            'parkingBrakeLockNearside'   => false,
            'parkingBrakeLockOffside'    => false
        ];
        return FitMotApiClient::createForCreds($credentialsProvider)->post(
            (new UrlBuilder())->motTest()->routeParam('motTestNumber', $motTestNumber)->brakeTestResult(),
            $data
        );
    }
}
