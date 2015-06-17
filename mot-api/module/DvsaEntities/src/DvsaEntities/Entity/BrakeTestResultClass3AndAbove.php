<?php

namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BrakeTestResultClass3AndAbove
 *
 * @ORM\Table(
 *   name="brake_test_result_class_3_and_above",
 *   options={"collate"="utf8_general_ci", "charset"="utf8", "engine"="InnoDB"}
 * )
 * @ORM\Entity
 */
class BrakeTestResultClass3AndAbove extends BrakeTestResult
{
    const DATE_BEFORE_CLASS_3_LOWER_EFFICIENCY = '1968-01-01';
    const DATE_BEFORE_CLASS_4_LOWER_EFFICIENCY = '2010-09-01';

    public function __construct()
    {
        $this->serviceBrakeIsSingleLine = false;
    }

    /**
     * @var \DvsaEntities\Entity\BrakeTestType
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\BrakeTestType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="service_brake_1_test_type_id", referencedColumnName="id")
     * })
     */
    private $serviceBrake1TestType;

    /**
     * @var \DvsaEntities\Entity\BrakeTestType
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\BrakeTestType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="service_brake_2_test_type_id", referencedColumnName="id")
     * })
     */
    private $serviceBrake2TestType;

    /**
     * @var \DvsaEntities\Entity\BrakeTestType
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\BrakeTestType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="parking_brake_test_type_id", referencedColumnName="id")
     * })
     */
    private $parkingBrakeTestType;

    /**
     * @var \DvsaEntities\Entity\BrakeTestResultServiceBrakeData
     *
     * @ORM\ManyToOne(
     *   targetEntity="DvsaEntities\Entity\BrakeTestResultServiceBrakeData", fetch="EAGER", cascade={"persist"}
     * )
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="service_brake_1_data_id", referencedColumnName="id")
     * })
     */
    private $serviceBrake1Data;

    /**
     * @var \DvsaEntities\Entity\BrakeTestResultServiceBrakeData
     *
     * @ORM\ManyToOne(
     *   targetEntity="DvsaEntities\Entity\BrakeTestResultServiceBrakeData", fetch="EAGER", cascade={"persist"}
     * )
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="service_brake_2_data_id", referencedColumnName="id")
     * })
     */
    private $serviceBrake2Data;

    /**
     * @var integer
     *
     * @ORM\Column(name="parking_brake_effort_nearside", type="integer", length=5, nullable=true)
     */
    private $parkingBrakeEffortNearside;

    /**
     * @var integer
     *
     * @ORM\Column(name="parking_brake_effort_offside", type="integer", length=5, nullable=true)
     */
    private $parkingBrakeEffortOffside;

    /**
     * @var integer
     *
     * @ORM\Column(name="parking_brake_effort_secondary_nearside", type="integer", length=5, nullable=true)
     */
    private $parkingBrakeEffortSecondaryNearside;

    /**
     * @var integer
     *
     * @ORM\Column(name="parking_brake_effort_secondary_offside", type="integer", length=5, nullable=true)
     */
    private $parkingBrakeEffortSecondaryOffside;

    /**
     * @var integer
     *
     * @ORM\Column(name="parking_brake_effort_single", type="integer", length=5, nullable=true)
     */
    private $parkingBrakeEffortSingle;

    /**
     * @var boolean
     *
     * @ORM\Column(name="parking_brake_lock_nearside", type="boolean", nullable=true)
     */
    private $parkingBrakeLockNearside;

    /**
     * @var boolean
     *
     * @ORM\Column(name="parking_brake_lock_offside", type="boolean", nullable=true)
     */
    private $parkingBrakeLockOffside;

    /**
     * @var boolean
     *
     * @ORM\Column(name="parking_brake_lock_secondary_nearside", type="boolean", nullable=true)
     */
    private $parkingBrakeLockSecondaryNearside;

    /**
     * @var boolean
     *
     * @ORM\Column(name="parking_brake_lock_secondary_offside", type="boolean", nullable=true)
     */
    private $parkingBrakeLockSecondaryOffside;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_single_in_front", type="boolean", nullable=true)
     */
    private $isSingleInFront;

    /**
     * @var boolean
     *
     * @ORM\Column(name="parking_brake_lock_single", type="boolean", nullable=true)
     */
    private $parkingBrakeLockSingle;

    /**
     * @var boolean
     *
     * @ORM\Column(name="service_brake_is_single_line", type="boolean", nullable=false)
     */
    private $serviceBrakeIsSingleLine;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_commercial_vehicle", type="boolean", nullable=true)
     */
    private $isCommercialVehicle;

    /**
     * @var integer
     *
     * @ORM\Column(name="parking_brake_imbalance", type="integer", nullable=true)
     */
    private $parkingBrakeImbalance;

    /**
     * @var integer
     *
     * @ORM\Column(name="parking_brake_secondary_imbalance", type="integer", nullable=true)
     */
    private $parkingBrakeSecondaryImbalance;

    /**
     * @var boolean
     *
     * @ORM\Column(name="parking_brake_imbalance_pass", type="boolean", nullable=true)
     */
    private $parkingBrakeImbalancePass;

    /**
     * @var integer
     *
     * @ORM\Column(name="vehicle_weight", type="integer", length=6, nullable=true)
     */
    private $vehicleWeight;

    /**
     * @var \DvsaEntities\Entity\WeightSource
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\WeightSource")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="weight_type_id", referencedColumnName="id")
     * })
     */
    private $weightType;

    /**
     * @var boolean
     *
     * @ORM\Column(name="weight_is_unladen", type="boolean", nullable=false)
     */
    private $weightIsUnladen;

    /**
     * @var integer
     *
     * @ORM\Column(name="service_brake_1_efficiency", type="integer", length=3, nullable=true)
     */
    private $serviceBrake1Efficiency;

    /**
     * @var integer
     *
     * @ORM\Column(name="service_brake_2_efficiency", type="integer", length=3, nullable=true)
     */
    private $serviceBrake2Efficiency;

    /**
     * @var integer
     *
     * @ORM\Column(name="parking_brake_efficiency", type="integer", length=3, nullable=true)
     */
    private $parkingBrakeEfficiency;

    /**
     * @var boolean
     *
     * @ORM\Column(name="service_brake_1_efficiency_pass", type="boolean", nullable=true)
     */
    private $serviceBrake1EfficiencyPass;

    /**
     * @var boolean
     *
     * @ORM\Column(name="service_brake_2_efficiency_pass", type="boolean", nullable=false)
     */
    private $serviceBrake2EfficiencyPass;

    /**
     * @var boolean
     *
     * @ORM\Column(name="parking_brake_efficiency_pass", type="boolean", nullable=true)
     */
    private $parkingBrakeEfficiencyPass;

    /**
     * @var \DvsaEntities\Entity\MotTest
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\MotTest", inversedBy="brakeTestResultClass3AndAboveHistory")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="mot_test_id", referencedColumnName="id")
     * })
     */
    private $motTest;

    /**
     * @var integer
     *
     * @ORM\Column(name="service_brake_total_axles_applied_to", nullable=true, type="integer")
     */
    private $numberOfAxles;

    /**
     * @var integer
     *
     * @ORM\Column(name="parking_brake_total_axles_applied_to", nullable=true, type="integer")
     */
    private $parkingBrakeNumberOfAxles;

    /**
     * @param BrakeTestType $serviceBrake1TestType
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setServiceBrake1TestType($serviceBrake1TestType)
    {
        $this->serviceBrake1TestType = $serviceBrake1TestType;

        return $this;
    }

    /**
     * @return BrakeTestType
     */
    public function getServiceBrake1TestType()
    {
        return $this->serviceBrake1TestType;
    }

    /**
     * @param BrakeTestType $serviceBrake2TestType
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setServiceBrake2TestType($serviceBrake2TestType)
    {
        $this->serviceBrake2TestType = $serviceBrake2TestType;

        return $this;
    }

    /**
     * @return BrakeTestType
     */
    public function getServiceBrake2TestType()
    {
        return $this->serviceBrake2TestType;
    }

    /**
     * @param BrakeTestType $parkingBrakeTestType
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setParkingBrakeTestType($parkingBrakeTestType)
    {
        $this->parkingBrakeTestType = $parkingBrakeTestType;

        return $this;
    }

    /**
     * @return BrakeTestType
     */
    public function getParkingBrakeTestType()
    {
        return $this->parkingBrakeTestType;
    }

    /**
     * Set parkingBrakeEffortNearside
     *
     * @param integer $parkingBrakeEffortNearside
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setParkingBrakeEffortNearside($parkingBrakeEffortNearside)
    {
        $this->parkingBrakeEffortNearside = $parkingBrakeEffortNearside;

        return $this;
    }

    /**
     * Get parkingBrakeEffortNearside
     *
     * @return integer
     */
    public function getParkingBrakeEffortNearside()
    {
        return $this->parkingBrakeEffortNearside;
    }

    /**
     * Set parkingBrakeEffortOffside
     *
     * @param integer $parkingBrakeEffortOffside
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setParkingBrakeEffortOffside($parkingBrakeEffortOffside)
    {
        $this->parkingBrakeEffortOffside = $parkingBrakeEffortOffside;

        return $this;
    }

    /**
     * Get parkingBrakeEffortOffside
     *
     * @return integer
     */
    public function getParkingBrakeEffortOffside()
    {
        return $this->parkingBrakeEffortOffside;
    }

    /**
     * Set parkingBrakeEffortSingle
     *
     * @param integer $parkingBrakeEffortSingle
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setParkingBrakeEffortSingle($parkingBrakeEffortSingle)
    {
        $this->parkingBrakeEffortSingle = $parkingBrakeEffortSingle;

        return $this;
    }

    /**
     * Get parkingBrakeEffortSingle
     *
     * @return integer
     */
    public function getParkingBrakeEffortSingle()
    {
        return $this->parkingBrakeEffortSingle;
    }

    /**
     * @param int $parkingBrakeEffortSecondaryNearside
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setParkingBrakeEffortSecondaryNearside($parkingBrakeEffortSecondaryNearside)
    {
        $this->parkingBrakeEffortSecondaryNearside = $parkingBrakeEffortSecondaryNearside;

        return $this;
    }

    /**
     * @return int
     */
    public function getParkingBrakeEffortSecondaryNearside()
    {
        return $this->parkingBrakeEffortSecondaryNearside;
    }

    /**
     * @param int $parkingBrakeEffortSecondaryOffside
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setParkingBrakeEffortSecondaryOffside($parkingBrakeEffortSecondaryOffside)
    {
        $this->parkingBrakeEffortSecondaryOffside = $parkingBrakeEffortSecondaryOffside;

        return $this;
    }

    /**
     * @return int
     */
    public function getParkingBrakeEffortSecondaryOffside()
    {
        return $this->parkingBrakeEffortSecondaryOffside;
    }

    /**
     * Set parkingBrakeLockNearside
     *
     * @param boolean $parkingBrakeLockNearside
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setParkingBrakeLockNearside($parkingBrakeLockNearside)
    {
        $this->parkingBrakeLockNearside = $parkingBrakeLockNearside;

        return $this;
    }

    /**
     * Get parkingBrakeLockNearside
     *
     * @return boolean
     */
    public function getParkingBrakeLockNearside()
    {
        return $this->parkingBrakeLockNearside;
    }

    /**
     * Set parkingBrakeLockOffside
     *
     * @param boolean $parkingBrakeLockOffside
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setParkingBrakeLockOffside($parkingBrakeLockOffside)
    {
        $this->parkingBrakeLockOffside = $parkingBrakeLockOffside;

        return $this;
    }

    /**
     * Get parkingBrakeLockOffside
     *
     * @return boolean
     */
    public function getParkingBrakeLockOffside()
    {
        return $this->parkingBrakeLockOffside;
    }

    /**
     * Set parkingBrakeLockSingle
     *
     * @param boolean $parkingBrakeLockSingle
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setParkingBrakeLockSingle($parkingBrakeLockSingle)
    {
        $this->parkingBrakeLockSingle = $parkingBrakeLockSingle;

        return $this;
    }

    /**
     * Get parkingBrakeLockSingle
     *
     * @return boolean
     */
    public function getParkingBrakeLockSingle()
    {
        return $this->parkingBrakeLockSingle;
    }

    /**
     * @param boolean $parkingBrakeLockSecondaryNearside
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setParkingBrakeLockSecondaryNearside($parkingBrakeLockSecondaryNearside)
    {
        $this->parkingBrakeLockSecondaryNearside = $parkingBrakeLockSecondaryNearside;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getParkingBrakeLockSecondaryNearside()
    {
        return $this->parkingBrakeLockSecondaryNearside;
    }

    /**
     * @param boolean $parkingBrakeLockSecondaryOffside
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setParkingBrakeLockSecondaryOffside($parkingBrakeLockSecondaryOffside)
    {
        $this->parkingBrakeLockSecondaryOffside = $parkingBrakeLockSecondaryOffside;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getParkingBrakeLockSecondaryOffside()
    {
        return $this->parkingBrakeLockSecondaryOffside;
    }

    /**
     * Set serviceBrakeIsSingleLine
     *
     * @param boolean $serviceBrakeIsSingleLine
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setServiceBrakeIsSingleLine($serviceBrakeIsSingleLine)
    {
        $this->serviceBrakeIsSingleLine = $serviceBrakeIsSingleLine;

        return $this;
    }

    /**
     * Get serviceBrakeIsSingleLine
     *
     * @return boolean
     */
    public function getServiceBrakeIsSingleLine()
    {
        return $this->serviceBrakeIsSingleLine;
    }

    /**
     * @param boolean $isCommercialVehicle
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setIsCommercialVehicle($isCommercialVehicle)
    {
        $this->isCommercialVehicle = $isCommercialVehicle;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsCommercialVehicle()
    {
        return $this->isCommercialVehicle;
    }

    /**
     * @param boolean $isSingleInFront
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setIsSingleInFront($isSingleInFront)
    {
        $this->isSingleInFront = $isSingleInFront;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsSingleInFront()
    {
        return $this->isSingleInFront;
    }

    /**
     * Set vehicleWeight
     *
     * @param integer $vehicleWeight
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setVehicleWeight($vehicleWeight)
    {
        $this->vehicleWeight = $vehicleWeight;

        return $this;
    }

    /**
     * Get vehicleWeight
     *
     * @return integer
     */
    public function getVehicleWeight()
    {
        return $this->vehicleWeight;
    }

    /**
     * @param WeightSource $weightType
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setWeightType($weightType)
    {
        $this->weightType = $weightType;

        return $this;
    }

    /**
     * @return WeightSource
     */
    public function getWeightType()
    {
        return $this->weightType;
    }

    /**
     * Set weightIsUnladen
     *
     * @param boolean $weightIsUnladen
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setWeightIsUnladen($weightIsUnladen)
    {
        $this->weightIsUnladen = $weightIsUnladen;

        return $this;
    }

    /**
     * Get weightIsUnladen
     *
     * @return boolean
     */
    public function getWeightIsUnladen()
    {
        return $this->weightIsUnladen;
    }

    /**
     * Set serviceBrake1Efficiency
     *
     * @param integer $serviceBrake1Efficiency
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setServiceBrake1Efficiency($serviceBrake1Efficiency)
    {
        $this->serviceBrake1Efficiency = $serviceBrake1Efficiency;

        return $this;
    }

    /**
     * Get serviceBrake1Efficiency
     *
     * @return integer
     */
    public function getServiceBrake1Efficiency()
    {
        return $this->serviceBrake1Efficiency;
    }

    /**
     * Set serviceBrake2Efficiency
     *
     * @param integer $serviceBrake2Efficiency
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setServiceBrake2Efficiency($serviceBrake2Efficiency)
    {
        $this->serviceBrake2Efficiency = $serviceBrake2Efficiency;

        return $this;
    }

    /**
     * Get serviceBrake2Efficiency
     *
     * @return integer
     */
    public function getServiceBrake2Efficiency()
    {
        return $this->serviceBrake2Efficiency;
    }

    /**
     * Set parkingBrakeEfficiency
     *
     * @param integer $parkingBrakeEfficiency
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setParkingBrakeEfficiency($parkingBrakeEfficiency)
    {
        $this->parkingBrakeEfficiency = $parkingBrakeEfficiency;

        return $this;
    }

    /**
     * Get parkingBrakeEfficiency
     *
     * @return integer
     */
    public function getParkingBrakeEfficiency()
    {
        return $this->parkingBrakeEfficiency;
    }

    /**
     * Set serviceBrake1EfficiencyPass
     *
     * @param boolean $serviceBrake1EfficiencyPass
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setServiceBrake1EfficiencyPass($serviceBrake1EfficiencyPass)
    {
        $this->serviceBrake1EfficiencyPass = $serviceBrake1EfficiencyPass;

        return $this;
    }

    /**
     * Get serviceBrake1EfficiencyPass
     *
     * @return boolean
     */
    public function getServiceBrake1EfficiencyPass()
    {
        return $this->serviceBrake1EfficiencyPass;
    }

    /**
     * Set serviceBrake2EfficiencyPass
     *
     * @param boolean $serviceBrake2EfficiencyPass
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setServiceBrake2EfficiencyPass($serviceBrake2EfficiencyPass)
    {
        $this->serviceBrake2EfficiencyPass = $serviceBrake2EfficiencyPass;

        return $this;
    }

    /**
     * Get serviceBrake2EfficiencyPass
     *
     * @return boolean
     */
    public function getServiceBrake2EfficiencyPass()
    {
        return $this->serviceBrake2EfficiencyPass;
    }

    /**
     * Set parkingBrakeEfficiencyPass
     *
     * @param boolean $parkingBrakeEfficiencyPass
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setParkingBrakeEfficiencyPass($parkingBrakeEfficiencyPass)
    {
        $this->parkingBrakeEfficiencyPass = $parkingBrakeEfficiencyPass;

        return $this;
    }

    /**
     * Get parkingBrakeEfficiencyPass
     *
     * @return boolean
     */
    public function getParkingBrakeEfficiencyPass()
    {
        return $this->parkingBrakeEfficiencyPass;
    }

    /**
     * Set serviceBrake1Data
     *
     * @param \DvsaEntities\Entity\BrakeTestResultServiceBrakeData $serviceBrake1Data
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setServiceBrake1Data(BrakeTestResultServiceBrakeData $serviceBrake1Data = null)
    {
        $this->serviceBrake1Data = $serviceBrake1Data;

        return $this;
    }

    /**
     * Get serviceBrake1Data
     *
     * @return \DvsaEntities\Entity\BrakeTestResultServiceBrakeData
     */
    public function getServiceBrake1Data()
    {
        return $this->serviceBrake1Data;
    }

    /**
     * Set serviceBrake2Data
     *
     * @param \DvsaEntities\Entity\BrakeTestResultServiceBrakeData $serviceBrake2Data
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setServiceBrake2Data(BrakeTestResultServiceBrakeData $serviceBrake2Data = null)
    {
        $this->serviceBrake2Data = $serviceBrake2Data;

        return $this;
    }

    /**
     * Get serviceBrake2Data
     *
     * @return \DvsaEntities\Entity\BrakeTestResultServiceBrakeData
     */
    public function getServiceBrake2Data()
    {
        return $this->serviceBrake2Data;
    }

    /**
     * @param int $val
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setParkingBrakeImbalance($val)
    {
        $this->parkingBrakeImbalance = $val;
        return $this;
    }

    /**
     * @return int
     */
    public function getParkingBrakeImbalance()
    {
        return $this->parkingBrakeImbalance;
    }

    /**
     * @param int $val
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setParkingBrakeSecondaryImbalance($val)
    {
        $this->parkingBrakeSecondaryImbalance = $val;
        return $this;
    }

    /**
     * @return int
     */
    public function getParkingBrakeSecondaryImbalance()
    {
        return $this->parkingBrakeSecondaryImbalance;
    }

    /**
     * @param boolean $val
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setParkingBrakeImbalancePass($val)
    {
        $this->parkingBrakeImbalancePass = $val;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getParkingBrakeImbalancePass()
    {
        return $this->parkingBrakeImbalancePass;
    }

    /**
     * @param \DvsaEntities\Entity\MotTest $motTest
     *
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
     * @param string $axles
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setNumberOfAxles($axles)
    {
        $this->numberOfAxles = $axles;
        return $this;
    }

    /**
     * @return string $serviceBrakeAxles
     */
    public function getNumberOfAxles()
    {
        return $this->numberOfAxles;
    }

    /**
     * @param string $axles
     *
     * @return BrakeTestResultClass3AndAbove
     */
    public function setParkingBrakeNumberOfAxles($axles)
    {
        $this->parkingBrakeNumberOfAxles = $axles;
        return $this;
    }

    /**
     * @return string $parkingBrakeAxles
     */
    public function getParkingBrakeNumberOfAxles()
    {
        return $this->parkingBrakeNumberOfAxles;
    }
}
