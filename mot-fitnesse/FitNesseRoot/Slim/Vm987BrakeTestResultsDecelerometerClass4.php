<?php

use DvsaCommon\Enum\BrakeTestTypeCode;
use DvsaCommon\Enum\VehicleClassCode;

class Vm987BrakeTestResultsDecelerometerClass4 extends BrakeTestClass3AndAboveBase
{
    protected $vehicleClassCode = VehicleClassCode::CLASS_4;

    public function serviceBrakeEfficiency()
    {
        return $this->valueOrError($this->brakeTestResult['serviceBrake1Efficiency']);
    }

    public function beforeExecute()
    {
        parent::beforeExecute();
        $this->brakeTestForm['serviceBrake1TestType'] = BrakeTestTypeCode::DECELEROMETER;
        $this->brakeTestForm['parkingBrakeTestType'] = BrakeTestTypeCode::DECELEROMETER;
    }

    public function setServiceBrakeEfficiency($value)
    {
        $this->brakeTestForm['serviceBrake1Efficiency'] = $value;
    }

    public function setParkingBrakeEfficiency($value)
    {
        $this->brakeTestForm['parkingBrakeEfficiency'] = $value;
    }
}
