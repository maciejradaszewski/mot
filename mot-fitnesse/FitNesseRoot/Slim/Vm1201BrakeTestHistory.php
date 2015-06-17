<?php

require_once 'configure_autoload.php';

use DvsaCommon\Enum\VehicleClassCode;
use MotFitnesse\Util\UrlBuilder;
use DvsaCommon\Enum\ColourCode;
use DvsaCommon\Enum\MotTestStatusName;
use DvsaCommon\Enum\BrakeTestTypeCode;

class Vm1201BrakeTestHistory
{
    /** @var \MotTestHelper */
    private $motTestHelper;
    /** @var \VehicleTestHelper */
    private $vehicleHelper;
    /** @var FitMotApiClient */
    private $api;

    private $brakeTestsToAdd;

    private $brakeTestClass1and2
        = [
            'brakeTestType'       => BrakeTestTypeCode::ROLLER,
            'vehicleWeightFront'  => 1,
            'vehicleWeightRear'   => 1,
            'riderWeight'         => 1,
            'control1EffortFront' => 1,
            'control2EffortFront' => 1,
            'control1EffortRear'  => 1
        ];

    private $brakeTestClass3andAbove
        = [
            'serviceBrake1TestType'   => BrakeTestTypeCode::DECELEROMETER,
            'parkingBrakeTestType'    => BrakeTestTypeCode::DECELEROMETER,
            'serviceBrake1Efficiency' => '50',
            'parkingBrakeEfficiency'  => '51'
        ];

    private $brakeTestClass;

    private $siteId;

    public function __construct($testerUsername, $siteId)
    {
        $credentialProvider = new \MotFitnesse\Util\CredentialsProvider(
            $testerUsername,
            \MotFitnesse\Util\TestShared::PASSWORD
        );
        $this->motTestHelper = new MotTestHelper($credentialProvider);
        $this->api = FitMotApiClient::createForCreds($credentialProvider);
        $this->vehicleHelper = new VehicleTestHelper($this->api);
        $this->siteId = $siteId;
    }

    public function setEditBrakeTestInMotTestType($value)
    {
        $this->brakeTestClass = $value;
    }

    public function setBrakeTestsToAdd($value)
    {
        $this->brakeTestsToAdd = (int)$value;
    }

    public function brakeTestCount()
    {
        if ($this->brakeTestClass != 'A' && $this->brakeTestClass != 'B') {
            throw new InvalidArgumentException(
                "$this->brakeTestClass is not a valid Brake Test Class. Expected 'A' or 'B'"
            );
        }

        $data = $this->brakeTestClass == 'A' ? $this->brakeTestClass1and2 : $this->brakeTestClass3andAbove;
        $vehicleClass = $this->brakeTestClass == 'A' ? VehicleClassCode::CLASS_2 : VehicleClassCode::CLASS_4;
        $vehicleId = $this->vehicleHelper->generateVehicle(['testClass' => $vehicleClass]);

        $motTestNumber = $this->motTestHelper->createMotTest(
            $vehicleId,
            null,
            $this->siteId,
            ColourCode::ORANGE,
            ColourCode::BLACK,
            true,
            $vehicleClass,
            'PE'
        )['motTestNumber'];
        $this->motTestHelper->odometerUpdate($motTestNumber);

        for ($i = 0; $i < $this->brakeTestsToAdd; ++$i) {
            $this->addBrakeTestResult($motTestNumber, $data);
        }

        $this->motTestHelper->changeStatus($motTestNumber, MotTestStatusName::PASSED);

        return $this->getBrakeTestResultCount($motTestNumber);
    }

    private function addBrakeTestResult($motTestNumber, $data)
    {
        $url = (new UrlBuilder())->motTest()->routeParam('motTestNumber', $motTestNumber)->brakeTestResult();
        $this->api->post($url, $data);
    }

    private function getBrakeTestResultCount($motTestNumber)
    {
        return $this->api->get(
            (new UrlBuilder())
                ->motTest()->routeParam('motTestNumber', $motTestNumber)
        )['brakeTestCount'];
    }
}
