<?php

use DvsaCommon\Enum\VehicleClassCode;

class Vm1028BrakeTestResultsClass3 extends BrakeTestClass3AndAboveBase
{
    protected $vehicleClassCode = VehicleClassCode::CLASS_3;


    public function setIsSingleInFront($value)
    {
        $this->brakeTestForm['isSingleInFront'] = ($value === self::YES) ? true : false;
    }

    public function setServiceBrakeEffortSingle($value)
    {
        $this->serviceBrake['effortSingle'] = $value;
    }

    public function setServiceBrakeLockSingle($value)
    {
        $this->serviceBrake['lockSingle'] = $this->lockToBool($value);
    }
}
