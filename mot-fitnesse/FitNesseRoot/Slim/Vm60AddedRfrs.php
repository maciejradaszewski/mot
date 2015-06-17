<?php

use DvsaCommon\Enum\BrakeTestTypeCode;
use DvsaCommon\Enum\VehicleClassCode;
use DvsaCommon\Enum\WeightSourceCode;

class Vm60AddedRfrs extends BrakeTestAddRfrBase
{
    protected $vehicleClassCode = VehicleClassCode::CLASS_4;
    private $serviceBrake = [];


    protected function beforeExecute()
    {
        $this->brakeTestForm['serviceBrake1Data'] = $this->serviceBrake;
    }

    public function setVehicleWeight($value)
    {
        $this->brakeTestForm['vehicleWeight'] = $value;
        $this->brakeTestForm['weightType'] = WeightSourceCode::PRESENTED;
        $this->brakeTestForm['serviceBrake1TestType'] = BrakeTestTypeCode::ROLLER;
        $this->brakeTestForm['parkingBrakeTestType'] = BrakeTestTypeCode::ROLLER;
        $this->serviceBrake['lockNearsideAxle1'] = false;
        $this->serviceBrake['lockOffsideAxle1'] = false;
        $this->serviceBrake['lockNearsideAxle2'] = false;
        $this->serviceBrake['lockOffsideAxle2'] = false;
        $this->brakeTestForm['parkingBrakeLockNearside'] = false;
        $this->brakeTestForm['parkingBrakeLockOffside'] = false;
        $this->brakeTestForm['numberOfAxles'] = '2';
    }

    public function setServiceBrakeNearsideAxle1($value)
    {
        $this->serviceBrake['effortNearsideAxle1'] = $value;
    }

    public function setServiceBrakeOffsideAxle1($value)
    {
        $this->serviceBrake['effortOffsideAxle1'] = $value;
    }

    public function setServiceBrakeNearsideAxle2($value)
    {
        $this->serviceBrake['effortNearsideAxle2'] = $value;
    }

    public function setServiceBrakeOffsideAxle2($value)
    {
        $this->serviceBrake['effortOffsideAxle2'] = $value;
    }

    public function setParkingBrakeNearside($value)
    {
        $this->brakeTestForm['parkingBrakeEffortNearside'] = $value;
    }

    public function setParkingBrakeOffside($value)
    {
        $this->brakeTestForm['parkingBrakeEffortOffside'] = $value;
    }

    public function setServiceBrakeIsSingleLine($value)
    {
        $this->brakeTestForm['serviceBrakeIsSingleLine'] = $value == 'YES';
    }

    public function serviceBrakePass()
    {
        return $this->passFailOrError($this->brakeTestResult['serviceBrake1EfficiencyPass']);
    }

    public function parkingBrakePass()
    {
        return $this->passFailOrError($this->brakeTestResult['parkingBrakeEfficiencyPass']);
    }

    public function imbalancePass()
    {
        return $this->passFailOrError($this->brakeTestResult['serviceBrake1Data']['imbalancePass']);
    }

    public function parkingBrakeImbalancePass()
    {
        return self::passFailOrNa($this->brakeTestResult['parkingBrakeImbalancePass']);
    }
}
