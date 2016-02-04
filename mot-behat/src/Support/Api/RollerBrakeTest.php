<?php
/**
 * Created by PhpStorm.
 * User: Shankar
 * Date: 02/02/2016
 * Time: 21:39
 */

namespace Dvsa\Mot\Behat\Support\Api;


class RollerBrakeTest
{
    private $control1EffortFront;
    private $control1EffortRear;
    private $control1EffortSidecar;
    private $control2EffortFront;
    private $control2EffortRear;
    private $control2EffortSidecar;
    private $control1LockFront;
    private $control1LockRear;
    private $control2LockFront;
    private $control2LockRear;
    private $vehicleWeightFront;
    private $vehicleWeightRear;
    private $riderWeight;
    private $isSideCarAttached;
    private $sidecarWeight;

    public function __construct($params)
    {
        $this->control1EffortFront = $params["control1EffortFront"];
        $this->control1EffortRear= $params["control1EffortRear"];
        $this->control1EffortSidecar = $params["control1EffortSidecar"];
        $this->control2EffortFront = $params["control2EffortFront"];
        $this->control2EffortRear = $params["control2EffortRear"];
        $this->control2EffortSidecar = $params["control2EffortSidecar"];
        $this->control1LockFront = $params["control1LockFront"];
        $this->control1LockRear = $params["control1LockRear"];
        $this->control2LockFront = $params["control2LockFront"];
        $this->control2LockRear = $params["control2LockRear"];
        $this->vehicleWeightFront = $params["vehicleWeightFront"];
        $this->vehicleWeightRear = $params["vehicleWeightRear"];
        $this->riderWeight = $params["riderWeight"];
        $this->isSideCarAttached = $params["isSideCarAttached"];
        $this->sidecarWeight = $params["sidecarWeight"];
    }

    public function getControl1EffortFront()
    {
        return $this->control1EffortFront;
    }

    public function getControl1EffortRear()
    {
        return $this->control1EffortRear;
    }

    public function getControl1EffortSidecar()
    {
        return $this->control1EffortSidecar;
    }

    public function getControl2EffortFront()
    {
        return $this->control2EffortFront;
    }

    public function getControl2EffortRear()
    {
        return $this->control2EffortRear;
    }

    public function getControl2EffortSidecar()
    {
        return $this->control2EffortSidecar;
    }

    public function getControl1LockFront()
    {
        return $this->control1LockFront;
    }

    public function getControl1LockRear()
    {
        return $this->control1LockRear;
    }

    public function getControl2LockFront()
    {
        return $this->control2LockFront;
    }

    public function getControl2LockRear()
    {
        return $this->control2LockRear;
    }

    public function getVehicleWeightFront()
    {
        return $this->vehicleWeightFront;
    }

    public function getVehicleWeightRear()
    {
        return $this->vehicleWeightRear;
    }

    public function RiderWeight()
    {
        return $this->riderWeight;
    }

    public function IsSideCarAttached()
    {
        return $this->isSideCarAttached;
    }

    public function SidecarWeight()
    {
        return $this->sidecarWeight;
    }


}