<?php

use DvsaCommon\Enum\BrakeTestTypeCode;
use DvsaCommon\Enum\VehicleClassCode;

class Vm860AddedRfrs extends BrakeTestAddRfrBase
{
    protected $vehicleClassCode = VehicleClassCode::CLASS_1;

    /**
     * @param mixed $control1EffortFront
     */
    public function setControl1EffortFront($control1EffortFront)
    {
        $this->brakeTestForm['control1EffortFront'] = $control1EffortFront;
        $this->brakeTestForm['brakeTestType'] = BrakeTestTypeCode::ROLLER;
        $this->brakeTestForm['control1LockFront'] = false;
        $this->brakeTestForm['control1LockRear'] = false;
        $this->brakeTestForm['control2LockFront'] = false;
        $this->brakeTestForm['control2LockRear'] = false;
        $this->brakeTestForm['riderWeight'] = 100;
        $this->brakeTestForm['sidecarWeight'] = null;
        $this->brakeTestForm['vehicleWeightFront'] = 150;
        $this->brakeTestForm['vehicleWeightRear'] = 150;
        $this->brakeTestForm['control1EffortSidecar'] = 0;
        $this->brakeTestForm['control2EffortSidecar'] = 0;
    }

    /**
     * @param mixed $control1EffortRear
     */
    public function setControl1EffortRear($control1EffortRear)
    {
        $this->brakeTestForm['control1EffortRear'] = $control1EffortRear;
    }

    /**
     * @param mixed $control2EffortFront
     */
    public function setControl2EffortFront($control2EffortFront)
    {
        $this->brakeTestForm['control2EffortFront'] = $control2EffortFront;
    }

    /**
     * @param mixed $control2EffortRear
     */
    public function setControl2EffortRear($control2EffortRear)
    {
        $this->brakeTestForm['control2EffortRear'] = $control2EffortRear;
    }

    public function control1Efficiency()
    {
        return $this->valueOrError($this->brakeTestResult['control1BrakeEfficiency']);
    }

    public function control2Efficiency()
    {
        return $this->valueOrError($this->brakeTestResult['control2BrakeEfficiency']);
    }

    public function generalPass()
    {
        return $this->passFailOrError($this->brakeTestResult['generalPass']);
    }
}
