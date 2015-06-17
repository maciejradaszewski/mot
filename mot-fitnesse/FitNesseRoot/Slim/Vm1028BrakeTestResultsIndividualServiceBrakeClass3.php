<?php

use DvsaCommon\Enum\BrakeTestTypeCode;
use DvsaCommon\Enum\VehicleClassCode;

class Vm1028BrakeTestResultsIndividualServiceBrakeClass3 extends Vm1028BrakeTestResultsClass3
{
    protected $serviceBrake2 = [];
    protected $vehicleClassCode = VehicleClassCode::CLASS_3;

    public function beforeExecute()
    {
        $this->brakeTestForm['serviceBrake2Data'] = $this->serviceBrake2;
        $this->brakeTestForm['serviceBrake2TestType'] = BrakeTestTypeCode::ROLLER;
        $this->brakeTestForm['serviceBrakeIsSingleLine'] = false;
        parent::beforeExecute();
    }

    public function serviceBrake1Efficiency()
    {
        return parent::serviceBrakeEfficiency();
    }

    public function serviceBrake2Efficiency()
    {
        return $this->valueOrError($this->brakeTestResult['serviceBrake2Efficiency']);
    }

    public function serviceBrake1Pass()
    {
        return parent::serviceBrakePass();
    }

    public function serviceBrake2Pass()
    {
        return $this->passFailOrError($this->brakeTestResult['serviceBrake2EfficiencyPass']);
    }

    public function setServiceBrakeNearsideAxle2($value)
    {
        $this->serviceBrake2['effortNearsideAxle1'] = $value;
    }

    public function setServiceBrakeOffsideAxle2($value)
    {
        $this->serviceBrake2['effortOffsideAxle1'] = $value;
    }

    public function setServiceBrakeNearsideAxle2Lock($value)
    {
        $this->serviceBrake2['lockNearsideAxle1'] = $this->lockToBool($value);
    }

    public function setServiceBrakeOffsideAxle2Lock($value)
    {
        $this->serviceBrake2['lockOffsideAxle1'] = $this->lockToBool($value);
    }

    //effort single and lock single for both
    public function setServiceBrakeEffortSingle($value)
    {
        $this->serviceBrake['effortSingle'] = $this->serviceBrake2['effortSingle'] = $value;
    }

    public function setServiceBrakeLockSingle($value)
    {
        $this->serviceBrake['lockSingle'] = $this->serviceBrake2['lockSingle'] = $this->lockToBool($value);
    }
}
