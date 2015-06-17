<?php

namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BrakeTestResultClass12
 *
 * @ORM\Table(name="brake_test_result_class_1_2")
 * @ORM\Entity
 */
class BrakeTestResultClass12 extends BrakeTestResult
{
    const DATE_FIRST_USED_ONLY_ONE_CONTROL_ALLOWED = '1927-01-01';

    /**
     * @var \DvsaEntities\Entity\BrakeTestType
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\BrakeTestType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="brake_test_type_id", referencedColumnName="id")
     * })
     */
    private $brakeTestType;

    /**
     * @var integer
     *
     * @ORM\Column(name="vehicle_weight_front", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $vehicleWeightFront;

    /**
     * @var integer
     *
     * @ORM\Column(name="vehicle_weight_rear", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $vehicleWeightRear;

    /**
     * @var integer
     *
     * @ORM\Column(name="rider_weight", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $riderWeight;

    /**
     * @var integer
     *
     * @ORM\Column(name="sidecar_weight", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $sidecarWeight;

    /**
     * @var integer
     *
     * @ORM\Column(name="control_1_effort_front", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $control1EffortFront;

    /**
     * @var integer
     *
     * @ORM\Column(name="control_1_effort_rear", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $control1EffortRear;

    /**
     * @var integer
     *
     * @ORM\Column(name="control_1_effort_sidecar", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $control1EffortSidecar;

    /**
     * @var integer
     *
     * @ORM\Column(name="control_2_effort_front", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $control2EffortFront;

    /**
     * @var integer
     *
     * @ORM\Column(name="control_2_effort_rear", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $control2EffortRear;

    /**
     * @var integer
     *
     * @ORM\Column(name="control_2_effort_sidecar", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $control2EffortSidecar;

    /**
     * @var boolean
     *
     * @ORM\Column(name="control_1_lock_front", type="boolean", precision=0, scale=0, nullable=true, unique=false)
     */
    private $control1LockFront;

    /**
     * @var boolean
     *
     * @ORM\Column(name="control_1_lock_rear", type="boolean", precision=0, scale=0, nullable=true, unique=false)
     */
    private $control1LockRear;

    /**
     * @var boolean
     *
     * @ORM\Column(name="control_2_lock_front", type="boolean", precision=0, scale=0, nullable=true, unique=false)
     */
    private $control2LockFront;

    /**
     * @var boolean
     *
     * @ORM\Column(name="control_2_lock_rear", type="boolean", precision=0, scale=0, nullable=true, unique=false)
     */
    private $control2LockRear;

    /**
     * @var integer
     *
     * @ORM\Column(name="control_1_brake_efficiency", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $control1BrakeEfficiency;

    /**
     * @var integer
     *
     * @ORM\Column(name="control_2_brake_efficiency", type="integer", precision=0, scale=0, nullable=true, unique=false)
     */
    private $control2BrakeEfficiency;

    /**
     * @var boolean
     *
     * @ORM\Column(name="control_1_efficiency_pass", type="boolean", precision=0, scale=0, nullable=false, unique=false)
     */
    private $control1EfficiencyPass;

    /**
     * @var boolean
     *
     * @ORM\Column(name="gradient_control_1_below_minimum", type="boolean", precision=0, scale=0, nullable=true, unique=false)
     */
    private $gradientControl1BelowMinimum;

    /**
     * @var boolean
     *
     * @ORM\Column(name="gradient_control_2_below_minimum", type="boolean", precision=0, scale=0, nullable=true, unique=false)
     */
    private $gradientControl2BelowMinimum;

    /**
     * @var boolean
     *
     * @ORM\Column(name="control_2_efficiency_pass", type="boolean", precision=0, scale=0, nullable=true, unique=false)
     */
    private $control2EfficiencyPass;

    /**
     * @var \DvsaEntities\Entity\MotTest
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\MotTest", inversedBy="brakeTestResultClass12History")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mot_test_id", referencedColumnName="id")
     * })
     */
    private $motTest;

    /**
     * @var boolean
     */
    private $gradientControl1AboveUpperMinimum;

    /**
     * @var boolean
     */
    private $gradientControl2AboveUpperMinimum;

    /**
     * @param BrakeTestType $brakeTestType
     *
     * @return BrakeTestResultClass12
     */
    public function setBrakeTestType($brakeTestType)
    {
        $this->brakeTestType = $brakeTestType;

        return $this;
    }

    /**
     * @return BrakeTestType
     */
    public function getBrakeTestType()
    {
        return $this->brakeTestType;
    }

    /**
     * Set vehicleWeightFront
     *
     * @param integer $vehicleWeightFront
     * @return BrakeTestResultClass12
     */
    public function setVehicleWeightFront($vehicleWeightFront)
    {
        $this->vehicleWeightFront = $vehicleWeightFront;

        return $this;
    }

    /**
     * Get vehicleWeightFront
     *
     * @return integer
     */
    public function getVehicleWeightFront()
    {
        return $this->vehicleWeightFront;
    }

    /**
     * Set vehicleWeightRear
     *
     * @param integer $vehicleWeightRear
     * @return BrakeTestResultClass12
     */
    public function setVehicleWeightRear($vehicleWeightRear)
    {
        $this->vehicleWeightRear = $vehicleWeightRear;

        return $this;
    }

    /**
     * Get vehicleWeightRear
     *
     * @return integer
     */
    public function getVehicleWeightRear()
    {
        return $this->vehicleWeightRear;
    }

    /**
     * Set riderWeight
     *
     * @param integer $riderWeight
     * @return BrakeTestResultClass12
     */
    public function setRiderWeight($riderWeight)
    {
        $this->riderWeight = $riderWeight;

        return $this;
    }

    /**
     * Get riderWeight
     *
     * @return integer
     */
    public function getRiderWeight()
    {
        return $this->riderWeight;
    }

    /**
     * Set sidecarWeight
     *
     * @param integer $sidecarWeight
     * @return BrakeTestResultClass12
     */
    public function setSidecarWeight($sidecarWeight)
    {
        $this->sidecarWeight = $sidecarWeight;

        return $this;
    }

    /**
     * Get sidecarWeight
     *
     * @return integer
     */
    public function getSidecarWeight()
    {
        return $this->sidecarWeight;
    }

    /**
     * Set control1EffortFront
     *
     * @param integer $control1EffortFront
     * @return BrakeTestResultClass12
     */
    public function setControl1EffortFront($control1EffortFront)
    {
        $this->control1EffortFront = $control1EffortFront;

        return $this;
    }

    /**
     * Get control1EffortFront
     *
     * @return integer
     */
    public function getControl1EffortFront()
    {
        return $this->control1EffortFront;
    }

    /**
     * Set control1EffortRear
     *
     * @param integer $control1EffortRear
     * @return BrakeTestResultClass12
     */
    public function setControl1EffortRear($control1EffortRear)
    {
        $this->control1EffortRear = $control1EffortRear;

        return $this;
    }

    /**
     * Get control1EffortRear
     *
     * @return integer
     */
    public function getControl1EffortRear()
    {
        return $this->control1EffortRear;
    }

    /**
     * Set control1EffortSidecar
     *
     * @param integer $control1EffortSidecar
     * @return BrakeTestResultClass12
     */
    public function setControl1EffortSidecar($control1EffortSidecar)
    {
        $this->control1EffortSidecar = $control1EffortSidecar;

        return $this;
    }

    /**
     * Get control1EffortSidecar
     *
     * @return integer
     */
    public function getControl1EffortSidecar()
    {
        return $this->control1EffortSidecar;
    }

    /**
     * Set control2EffortFront
     *
     * @param integer $control2EffortFront
     * @return BrakeTestResultClass12
     */
    public function setControl2EffortFront($control2EffortFront)
    {
        $this->control2EffortFront = $control2EffortFront;

        return $this;
    }

    /**
     * Get control2EffortFront
     *
     * @return integer
     */
    public function getControl2EffortFront()
    {
        return $this->control2EffortFront;
    }

    /**
     * Set control2EffortRear
     *
     * @param integer $control2EffortRear
     * @return BrakeTestResultClass12
     */
    public function setControl2EffortRear($control2EffortRear)
    {
        $this->control2EffortRear = $control2EffortRear;

        return $this;
    }

    /**
     * Get control2EffortRear
     *
     * @return integer
     */
    public function getControl2EffortRear()
    {
        return $this->control2EffortRear;
    }

    /**
     * Set control2EffortSidecar
     *
     * @param integer $control2EffortSidecar
     * @return BrakeTestResultClass12
     */
    public function setControl2EffortSidecar($control2EffortSidecar)
    {
        $this->control2EffortSidecar = $control2EffortSidecar;

        return $this;
    }

    /**
     * Get control2EffortSidecar
     *
     * @return integer
     */
    public function getControl2EffortSidecar()
    {
        return $this->control2EffortSidecar;
    }

    /**
     * Set control1LockFront
     *
     * @param boolean $control1LockFront
     * @return BrakeTestResultClass12
     */
    public function setControl1LockFront($control1LockFront)
    {
        $this->control1LockFront = $control1LockFront;

        return $this;
    }

    /**
     * Get control1LockFront
     *
     * @return boolean
     */
    public function getControl1LockFront()
    {
        return $this->control1LockFront;
    }

    /**
     * Set control1LockRear
     *
     * @param boolean $control1LockRear
     * @return BrakeTestResultClass12
     */
    public function setControl1LockRear($control1LockRear)
    {
        $this->control1LockRear = $control1LockRear;

        return $this;
    }

    /**
     * Get control1LockRear
     *
     * @return boolean
     */
    public function getControl1LockRear()
    {
        return $this->control1LockRear;
    }

    /**
     * Set control2LockFront
     *
     * @param boolean $control2LockFront
     * @return BrakeTestResultClass12
     */
    public function setControl2LockFront($control2LockFront)
    {
        $this->control2LockFront = $control2LockFront;

        return $this;
    }

    /**
     * Get control2LockFront
     *
     * @return boolean
     */
    public function getControl2LockFront()
    {
        return $this->control2LockFront;
    }

    /**
     * Set control2LockRear
     *
     * @param boolean $control2LockRear
     * @return BrakeTestResultClass12
     */
    public function setControl2LockRear($control2LockRear)
    {
        $this->control2LockRear = $control2LockRear;

        return $this;
    }

    /**
     * Get control2LockRear
     *
     * @return boolean
     */
    public function getControl2LockRear()
    {
        return $this->control2LockRear;
    }

    /**
     * Set control1BrakeEfficiency
     *
     * @param integer $control1BrakeEfficiency
     * @return BrakeTestResultClass12
     */
    public function setControl1BrakeEfficiency($control1BrakeEfficiency)
    {
        $this->control1BrakeEfficiency = $control1BrakeEfficiency;

        return $this;
    }

    /**
     * Get control1BrakeEfficiency
     *
     * @return integer
     */
    public function getControl1BrakeEfficiency()
    {
        return $this->control1BrakeEfficiency;
    }

    /**
     * Set control2BrakeEfficiency
     *
     * @param integer $control2BrakeEfficiency
     * @return BrakeTestResultClass12
     */
    public function setControl2BrakeEfficiency($control2BrakeEfficiency)
    {
        $this->control2BrakeEfficiency = $control2BrakeEfficiency;

        return $this;
    }

    /**
     * Get control2BrakeEfficiency
     *
     * @return integer
     */
    public function getControl2BrakeEfficiency()
    {
        return $this->control2BrakeEfficiency;
    }

    /**
     * @param boolean $gradientControlsBelowMinimum
     * @return BrakeTestResultClass12
     */
    public function setGradientControl1BelowMinimum($gradientControlsBelowMinimum)
    {
        $this->gradientControl1BelowMinimum = $gradientControlsBelowMinimum;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getGradientControl1BelowMinimum()
    {
        return $this->gradientControl1BelowMinimum;
    }

    /**
     * @param boolean $gradientControlsBelowMinimum
     * @return BrakeTestResultClass12
     */
    public function setGradientControl2BelowMinimum($gradientControlsBelowMinimum)
    {
        $this->gradientControl2BelowMinimum = $gradientControlsBelowMinimum;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getGradientControl2BelowMinimum()
    {
        return $this->gradientControl2BelowMinimum;
    }

    /**
     * Set control1EfficiencyPass
     *
     * @param boolean $control1EfficiencyPass
     * @return BrakeTestResultClass12
     */
    public function setControl1EfficiencyPass($control1EfficiencyPass)
    {
        $this->control1EfficiencyPass = $control1EfficiencyPass;

        return $this;
    }

    /**
     * Get control1EfficiencyPass
     *
     * @return boolean
     */
    public function getControl1EfficiencyPass()
    {
        return $this->control1EfficiencyPass;
    }

    /**
     * Set control2EfficiencyPass
     *
     * @param boolean $control2EfficiencyPass
     * @return BrakeTestResultClass12
     */
    public function setControl2EfficiencyPass($control2EfficiencyPass)
    {
        $this->control2EfficiencyPass = $control2EfficiencyPass;

        return $this;
    }

    /**
     * Get control2EfficiencyPass
     *
     * @return boolean
     */
    public function getControl2EfficiencyPass()
    {
        return $this->control2EfficiencyPass;
    }

    /**
     * @param \DvsaEntities\Entity\MotTest $motTest
     * @return BrakeTestResultClass12
     */
    public function setMotTest($motTest)
    {
        $this->motTest = $motTest;
        return $this;
    }

    /**
     * @return \DvsaEntities\Entity\MotTest
     */
    public function getMotTest()
    {
        return $this->motTest;
    }

    /**
     * @param mixed $gradientControl1AboveUpperMinimum
     * @return BrakeTestResultClass12
     */
    public function setGradientControl1AboveUpperMinimum($gradientControl1AboveUpperMinimum)
    {
        $this->gradientControl1AboveUpperMinimum = $gradientControl1AboveUpperMinimum;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGradientControl1AboveUpperMinimum()
    {
        return $this->gradientControl1AboveUpperMinimum;
    }

    /**
     * @param mixed $gradientControl2AboveUpperMinimum
     * @return BrakeTestResultClass12
     */
    public function setGradientControl2AboveUpperMinimum($gradientControl2AboveUpperMinimum)
    {
        $this->gradientControl2AboveUpperMinimum = $gradientControl2AboveUpperMinimum;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getGradientControl2AboveUpperMinimum()
    {
        return $this->gradientControl2AboveUpperMinimum;
    }
}
