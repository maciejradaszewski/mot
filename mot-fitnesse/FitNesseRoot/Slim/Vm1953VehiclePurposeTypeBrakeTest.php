<?php

use DvsaCommon\Enum\BrakeTestTypeCode;
use DvsaCommon\Enum\VehicleClassCode;

class Vm1953VehiclePurposeTypeBrakeTest extends BrakeTestClass3AndAboveBase
{
    protected $vehicleClassCode = VehicleClassCode::CLASS_4;

    protected function resolveVehicleId(VehicleTestHelper $vehicleTestHelper)
    {
        $vehicleData = ['testClass' => $this->vehicleClassCode, 'dateOfFirstUse' => '2013-07-01'];
        $vehicleId = $vehicleTestHelper->generateVehicle($vehicleData);
        return $vehicleId;
    }

    public function serviceBrakeEfficiency()
    {
        return $this->valueOrError($this->brakeTestResult['serviceBrake1Efficiency']);
    }

    public function beforeExecute()
    {
        parent::beforeExecute();
        $this->brakeTestForm['serviceBrake1TestType'] = BrakeTestTypeCode::DECELEROMETER;
        $this->brakeTestForm['parkingBrakeTestType'] = BrakeTestTypeCode::DECELEROMETER;
        $this->brakeTestForm['parkingBrakeEfficiency'] = 30;
    }

    public function setServiceBrakeEfficiency($value)
    {
        $this->brakeTestForm['serviceBrake1Efficiency'] = $value;
    }

    public function setVehiclePurposeType($value)
    {
        $this->brakeTestForm['isCommercialVehicle'] = (bool)($value === "GOODS");
    }
}
