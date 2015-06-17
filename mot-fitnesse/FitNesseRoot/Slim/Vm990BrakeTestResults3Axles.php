<?php

use DvsaCommon\Enum\VehicleClassCode;

class Vm990BrakeTestResults3Axles extends BrakeTestClass3AndAboveBase
{
    protected $vehicleClassCode = VehicleClassCode::CLASS_5;

    public function beforeExecute()
    {
        $this->setVehicleWeight(1000);
        $this->setServiceBrakeNearsideAxle1(100);
        $this->setServiceBrakeOffsideAxle1(100);
        $this->setServiceBrakeNearsideAxle2(100);
        $this->setServiceBrakeOffsideAxle2(100);
        $this->setParkingBrakeNearside(50);
        $this->setParkingBrakeOffside(50);
        $this->setServiceBrakeNearsideAxle1Lock(true);
        $this->setServiceBrakeOffsideAxle1Lock(true);
        $this->setServiceBrakeNearsideAxle2Lock(false);
        $this->setServiceBrakeOffsideAxle2Lock(false);
        $this->setParkingBrakeNearsideLock(true);
        $this->setParkingBrakeOffsideLock(false);
        parent::beforeExecute();
    }

    public function setServiceBrakeType($value)
    {
        $this->brakeTestForm['serviceBrakeIsSingleLine'] = $value === self::SERVICE_BRAKE_TYPE_DUAL_LINE ? false : true;
    }

    public function setServiceBrakeNearsideAxle3($value)
    {
        $this->serviceBrake['effortNearsideAxle3'] = $value;
    }

    public function setServiceBrakeOffsideAxle3($value)
    {
        $this->serviceBrake['effortOffsideAxle3'] = $value;
    }

    public function setParkingBrakeSecondaryNearside($value)
    {
        $this->brakeTestForm['parkingBrakeEffortSecondaryNearside'] = $value;
    }

    public function setParkingBrakeSecondaryOffside($value)
    {
        $this->brakeTestForm['parkingBrakeEffortSecondaryOffside'] = $value;
    }

    public function setServiceBrakeOffsideAxle3Lock($value)
    {
        $this->serviceBrake['lockOffsideAxle3'] = $this->lockToBool($value);
    }

    public function setServiceBrakeNearsideAxle3Lock($value)
    {
        $this->serviceBrake['lockNearsideAxle3'] = $this->lockToBool($value);
    }

    public function setParkingBrakeSecondaryNearsideLock($value)
    {
        $this->brakeTestForm['parkingBrakeLockSecondaryNearside'] = $this->lockToBool($value);
    }

    public function setParkingBrakeSecondaryOffsideLock($value)
    {
        $this->brakeTestForm['parkingBrakeLockSecondaryOffside'] = $this->lockToBool($value);
    }

    public function parkingBrakeSecondaryImbalance()
    {
        return $this->valueNaOrError($this->brakeTestResult['parkingBrakeSecondaryImbalance']);
    }
}
