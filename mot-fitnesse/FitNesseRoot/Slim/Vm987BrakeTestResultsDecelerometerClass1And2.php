<?php

use DvsaCommon\Enum\BrakeTestTypeCode;
use DvsaCommon\Enum\VehicleClassCode;

class Vm987BrakeTestResultsDecelerometerClass1And2 extends BrakeTestClass1And2Base
{
    protected $vehicleClassCode = VehicleClassCode::CLASS_1;

    /**
     * @param mixed $efficiency1
     */
    public function setControl1BrakeEfficiency($efficiency1)
    {
        $this->brakeTestForm['control1BrakeEfficiency'] = $efficiency1;
    }

    /**
     * @param mixed $efficiency2
     */
    public function setControl2BrakeEfficiency($efficiency2)
    {
        $this->brakeTestForm['control2BrakeEfficiency'] = $efficiency2;
    }

    public function beforeExecute()
    {
        $this->brakeTestForm['brakeTestType'] = BrakeTestTypeCode::DECELEROMETER;
    }
}
