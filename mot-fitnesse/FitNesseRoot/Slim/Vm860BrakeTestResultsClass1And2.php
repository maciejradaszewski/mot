<?php

use DvsaCommon\Enum\BrakeTestTypeCode;
use DvsaCommon\Enum\VehicleClassCode;

class Vm860BrakeTestResultsClass1And2 extends BrakeTestClass1And2Base
{
    protected $vehicleClassCode = VehicleClassCode::CLASS_1;

    public function beforeExecute()
    {
        $this->brakeTestForm['brakeTestType'] = BrakeTestTypeCode::ROLLER;
    }
    /**
     * @param mixed $control1EffortFront
     */
    public function setControl1EffortFront($control1EffortFront)
    {
        $this->brakeTestForm['control1EffortFront'] = $control1EffortFront;
    }

    /**
     * @param mixed $control1EffortRear
     */
    public function setControl1EffortRear($control1EffortRear)
    {
        $this->brakeTestForm['control1EffortRear'] = $control1EffortRear;
    }

    /**
     * @param mixed $control1EffortSidecar
     */
    public function setControl1EffortSidecar($control1EffortSidecar)
    {
        $this->brakeTestForm['control1EffortSidecar'] = $control1EffortSidecar;
    }

    /**
     * @param mixed $control1LockFront
     */
    public function setControl1LockFront($control1LockFront)
    {
        $this->brakeTestForm['control1LockFront'] = $this->lockToBool($control1LockFront);
    }

    /**
     * @param mixed $control1LockRear
     */
    public function setControl1LockRear($control1LockRear)
    {
        $this->brakeTestForm['control1LockRear'] = $this->lockToBool($control1LockRear);
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

    /**
     * @param mixed $control2EffortSidecar
     */
    public function setControl2EffortSidecar($control2EffortSidecar)
    {
        $this->brakeTestForm['control2EffortSidecar'] = $control2EffortSidecar;
    }

    /**
     * @param mixed $control2LockFront
     */
    public function setControl2LockFront($control2LockFront)
    {
        $this->brakeTestForm['control2LockFront'] = $this->lockToBool($control2LockFront);
    }

    /**
     * @param mixed $control2LockRear
     */
    public function setControl2LockRear($control2LockRear)
    {
        $this->brakeTestForm['control2LockRear'] = $this->lockToBool($control2LockRear);
    }
}
