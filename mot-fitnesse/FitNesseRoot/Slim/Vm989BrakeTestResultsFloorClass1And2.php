<?php

use DvsaCommon\Enum\BrakeTestTypeCode;
use DvsaCommon\Enum\VehicleClassCode;

class Vm989BrakeTestResultsFloorClass1And2 extends BrakeTestClass1And2Base
{
    protected $vehicleClassCode = VehicleClassCode::CLASS_1;

    /**
     * @param mixed $control1EffortFront
     */
    public function setControl1Effort($control1EffortFront)
    {
        $this->brakeTestForm['control1EffortFront'] = $control1EffortFront;
    }

    /**
     * @param mixed $control2EffortFront
     */
    public function setControl2Effort($control2EffortFront)
    {
        $this->brakeTestForm['control2EffortFront'] = $control2EffortFront;
    }

    public function beforeExecute()
    {
        $this->brakeTestForm['brakeTestType'] = BrakeTestTypeCode::FLOOR;
    }
}
