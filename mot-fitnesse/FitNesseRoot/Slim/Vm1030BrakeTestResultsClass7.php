<?php

use DvsaCommon\Enum\VehicleClassCode;

class Vm1030BrakeTestResultsClass7 extends BrakeTestClass3AndAboveBase
{
    protected $vehicleClassCode = VehicleClassCode::CLASS_7;

    public function setServiceBrakeType($value)
    {
        $this->brakeTestForm['serviceBrakeIsSingleLine'] = $value === self::SERVICE_BRAKE_TYPE_DUAL_LINE ? false : true;
    }

    public function setTestedUnladen($value)
    {
        $this->brakeTestForm['weightIsUnladen'] = $value === self::YES;
    }

    public function setServiceBrakeNearsideAxle3($value)
    {
        if (is_numeric($value)) {
            $this->serviceBrake['effortNearsideAxle3'] = self::textNullToNull($value);
        }

    }

    public function setServiceBrakeOffsideAxle3($value)
    {
        if (is_numeric($value)) {
            $this->serviceBrake['effortOffsideAxle3'] = self::textNullToNull($value);
        }
    }
}
