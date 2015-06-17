<?php

use DvsaCommon\Enum\VehicleClassCode;

class Vm1029BrakeTestResultsClass5 extends BrakeTestClass3AndAboveBase
{
    protected $vehicleClassCode = VehicleClassCode::CLASS_5;

    public function setServiceBrakeType($value)
    {
        $this->brakeTestForm['serviceBrakeIsSingleLine'] = $value === self::SERVICE_BRAKE_TYPE_DUAL_LINE ? false : true;
    }
}
