<?php

use DvsaCommon\Enum\BrakeTestTypeCode;
use DvsaCommon\Enum\WeightSourceCode;

abstract class BrakeTestClass3AndAboveBase extends BrakeTestBase
{
    const SERVICE_BRAKE_TYPE_SINGLE_LINE = 'SINGLE';
    const SERVICE_BRAKE_TYPE_DUAL_LINE = 'DUAL';

    protected $serviceBrake = [];

    public function setVehicleWeight($value)
    {
        $this->brakeTestForm['vehicleWeight'] = $value;
    }

    public function setServiceBrakeType($value)
    {
        $valueForCall = null;
        if ($value === self::SERVICE_BRAKE_TYPE_DUAL_LINE) {
            $valueForCall = false;
        } elseif ($value === self::SERVICE_BRAKE_TYPE_SINGLE_LINE) {
            $valueForCall = true;
        } else {
            throw new \Exception("Unknown brake line type [$value]");
        }
        $this->brakeTestForm['serviceBrakeIsSingleLine'] = $valueForCall;
    }

    public function setServiceBrakeNearsideAxle1($value)
    {
        $this->serviceBrake['effortNearsideAxle1'] = self::textNullToNull($value);
    }

    public function setServiceBrakeOffsideAxle1($value)
    {
        $this->serviceBrake['effortOffsideAxle1'] = self::textNullToNull($value);
    }

    public function setServiceBrakeNearsideAxle2($value)
    {
        $this->serviceBrake['effortNearsideAxle2'] = self::textNullToNull($value);
    }

    public function setServiceBrakeOffsideAxle2($value)
    {
        $this->serviceBrake['effortOffsideAxle2'] = self::textNullToNull($value);
    }

    public function setParkingBrakeNearside($value)
    {
        $this->brakeTestForm['parkingBrakeEffortNearside'] = $value;
    }

    public function setParkingBrakeOffside($value)
    {
        $this->brakeTestForm['parkingBrakeEffortOffside'] = $value;
    }

    public function setServiceBrakeNearsideAxle1Lock($value)
    {
        $this->serviceBrake['lockNearsideAxle1'] = $this->lockToBool($value);
    }

    public function setServiceBrakeOffsideAxle1Lock($value)
    {
        $this->serviceBrake['lockOffsideAxle1'] = $this->lockToBool($value);
    }

    public function setServiceBrakeNearsideAxle2Lock($value)
    {
        $this->serviceBrake['lockNearsideAxle2'] = $this->lockToBool($value);
    }

    public function setServiceBrakeOffsideAxle2Lock($value)
    {
        $this->serviceBrake['lockOffsideAxle2'] = $this->lockToBool($value);
    }

    public function setParkingBrakeNearsideLock($value)
    {
        $this->brakeTestForm['parkingBrakeLockNearside'] = $this->lockToBool($value);
    }

    public function setParkingBrakeOffsideLock($value)
    {
        $this->brakeTestForm['parkingBrakeLockOffside'] = $this->lockToBool($value);
    }

    public function beforeExecute()
    {
        $this->brakeTestForm['serviceBrake1Data'] = $this->serviceBrake;
        $this->brakeTestForm['serviceBrake1TestType'] = BrakeTestTypeCode::ROLLER;
        $this->brakeTestForm['parkingBrakeTestType'] = BrakeTestTypeCode::ROLLER;
        $this->brakeTestForm['weightType'] = WeightSourceCode::PRESENTED;
    }

    protected function afterExecute()
    {
        $this->serviceBrake = [];
    }

    public function serviceBrakeEfficiency()
    {
        return $this->valueOrError($this->brakeTestResult['serviceBrake1Efficiency']);
    }

    public function parkingBrakeEfficiency()
    {
        return $this->valueOrError($this->brakeTestResult['parkingBrakeEfficiency']);
    }

    public function frontBrakeImbalance()
    {
        return $this->valueNaOrError($this->brakeTestResult['serviceBrake1Data']['imbalanceAxle1']);
    }

    public function rearBrakeImbalance()
    {
        return $this->valueNaOrError($this->brakeTestResult['serviceBrake1Data']['imbalanceAxle2']);
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
        return $this->passFailNaOrError($this->brakeTestResult['serviceBrake1Data']['imbalancePass']);
    }

    public function parkingBrakeImbalance()
    {
        return $this->valueNaOrError($this->brakeTestResult['parkingBrakeImbalance']);
    }

    public function parkingBrakeImbalancePass()
    {
        return $this->passFailNaOrError($this->brakeTestResult['parkingBrakeImbalancePass']);
    }
}
