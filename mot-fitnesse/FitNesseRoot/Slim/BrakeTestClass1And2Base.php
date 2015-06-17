<?php

class BrakeTestClass1And2Base extends BrakeTestBase
{
    public function setRiderWeight($riderWeight)
    {
        $this->brakeTestForm['riderWeight'] = $riderWeight ? $riderWeight : null;
    }

    public function setSidecarWeight($sidecarWeight)
    {
        $this->brakeTestForm['sidecarWeight'] = $sidecarWeight ? $sidecarWeight : null;
    }

    public function setVehicleWeightFront($vehicleWeightFront)
    {
        $this->brakeTestForm['vehicleWeightFront'] = $vehicleWeightFront;
    }

    public function setVehicleWeightRear($vehicleWeightRear)
    {
        $this->brakeTestForm['vehicleWeightRear'] = $vehicleWeightRear;
    }

    public function control1Efficiency()
    {
        return $this->valueOrError($this->brakeTestResult['control1BrakeEfficiency']);
    }

    public function control2Efficiency()
    {
        return $this->valueOrError($this->brakeTestResult['control2BrakeEfficiency']);
    }

    public function control1Pass()
    {
        return $this->passFailOrError($this->brakeTestResult['control1EfficiencyPass']);
    }

    public function control2Pass()
    {
        return $this->passFailOrError($this->brakeTestResult['control2EfficiencyPass']);
    }
}
