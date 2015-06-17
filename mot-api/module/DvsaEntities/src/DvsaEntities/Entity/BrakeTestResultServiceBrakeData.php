<?php
namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;

/**
 * BrakeTestResultServiceBrakeData
 *
 * @ORM\Table(name="brake_test_result_service_brake_data")
 * @ORM\Entity
 */
class BrakeTestResultServiceBrakeData extends Entity
{
    use CommonIdentityTrait;

    /**
     * @var integer
     *
     * @ORM\Column(name="effort_nearside_axle1", type="integer", nullable=true)
     */
    private $effortNearsideAxle1;

    /**
     * @var integer
     *
     * @ORM\Column(name="effort_offside_axle1", type="integer", nullable=true)
     */
    private $effortOffsideAxle1;

    /**
     * @var integer
     *
     * @ORM\Column(name="effort_nearside_axle2", type="integer", nullable=true)
     */
    private $effortNearsideAxle2;

    /**
     * @var integer
     *
     * @ORM\Column(name="effort_offside_axle2", type="integer", nullable=true)
     */
    private $effortOffsideAxle2;

    /**
     * @var integer
     *
     * @ORM\Column(name="effort_nearside_axle3", type="integer", nullable=true)
     */
    private $effortNearsideAxle3;

    /**
     * @var integer
     *
     * @ORM\Column(name="effort_offside_axle3", type="integer", nullable=true)
     */
    private $effortOffsideAxle3;

    /**
     * @var integer
     *
     * @ORM\Column(name="effort_single", type="integer", nullable=true)
     */
    private $effortSingle;

    /**
     * @var boolean
     *
     * @ORM\Column(name="lock_nearside_axle1", type="boolean", nullable=true)
     */
    private $lockNearsideAxle1;

    /**
     * @var boolean
     *
     * @ORM\Column(name="lock_offside_axle1", type="boolean", nullable=true)
     */
    private $lockOffsideAxle1;

    /**
     * @var boolean
     *
     * @ORM\Column(name="lock_nearside_axle2", type="boolean", nullable=true)
     */
    private $lockNearsideAxle2;

    /**
     * @var boolean
     *
     * @ORM\Column(name="lock_offside_axle2", type="boolean", nullable=true)
     */
    private $lockOffsideAxle2;

    /**
     * @var boolean
     *
     * @ORM\Column(name="lock_nearside_axle3", type="boolean", nullable=true)
     */
    private $lockNearsideAxle3;

    /**
     * @var boolean
     *
     * @ORM\Column(name="lock_offside_axle3", type="boolean", nullable=true)
     */
    private $lockOffsideAxle3;

    /**
     * @var boolean
     *
     * @ORM\Column(name="lock_single", type="boolean", nullable=true)
     */
    private $lockSingle;

    /**
     * @var integer
     *
     * @ORM\Column(name="imbalance_axle1", type="integer", nullable=true)
     */
    private $imbalanceAxle1;

    /**
     * @var integer
     *
     * @ORM\Column(name="imbalance_axle2", type="integer", nullable=true)
     */
    private $imbalanceAxle2;

    /**
     * @var integer
     *
     * @ORM\Column(name="imbalance_axle3", type="integer", nullable=true)
     */
    private $imbalanceAxle3;

    /**
     * @var boolean
     *
     * @ORM\Column(name="imbalance_pass", type="boolean", nullable=true)
     */
    private $imbalancePass;

    /**
     * Stores imbalance pass/fail per axle (NON PERSISTENT)
     *
     * @var array
     */
    private $imbalancePassForAxle = [];

    /**
     * Set effortNearsideAxle1
     *
     * @param integer $effortNearsideAxle1
     * @return BrakeTestResultServiceBrakeData
     */
    public function setEffortNearsideAxle1($effortNearsideAxle1)
    {
        $this->effortNearsideAxle1 = $effortNearsideAxle1;

        return $this;
    }

    /**
     * Get effortNearsideAxle1
     *
     * @return integer
     */
    public function getEffortNearsideAxle1()
    {
        return $this->effortNearsideAxle1;
    }

    /**
     * Set effortOffsideAxle1
     *
     * @param integer $effortOffsideAxle1
     * @return BrakeTestResultServiceBrakeData
     */
    public function setEffortOffsideAxle1($effortOffsideAxle1)
    {
        $this->effortOffsideAxle1 = $effortOffsideAxle1;

        return $this;
    }

    /**
     * Get effortOffsideAxle1
     *
     * @return integer
     */
    public function getEffortOffsideAxle1()
    {
        return $this->effortOffsideAxle1;
    }

    /**
     * Set effortNearsideAxle2
     *
     * @param integer $effortNearsideAxle2
     * @return BrakeTestResultServiceBrakeData
     */
    public function setEffortNearsideAxle2($effortNearsideAxle2)
    {
        $this->effortNearsideAxle2 = $effortNearsideAxle2;

        return $this;
    }

    /**
     * Get effortNearsideAxle2
     *
     * @return integer
     */
    public function getEffortNearsideAxle2()
    {
        return $this->effortNearsideAxle2;
    }

    /**
     * Set effortOffsideAxle2
     *
     * @param integer $effortOffsideAxle2
     * @return BrakeTestResultServiceBrakeData
     */
    public function setEffortOffsideAxle2($effortOffsideAxle2)
    {
        $this->effortOffsideAxle2 = $effortOffsideAxle2;

        return $this;
    }

    /**
     * Get effortOffsideAxle2
     *
     * @return integer
     */
    public function getEffortOffsideAxle2()
    {
        return $this->effortOffsideAxle2;
    }

    /**
     * Set effortNearsideAxle3
     *
     * @param integer $effortNearsideAxle3
     * @return BrakeTestResultServiceBrakeData
     */
    public function setEffortNearsideAxle3($effortNearsideAxle3)
    {
        $this->effortNearsideAxle3 = $effortNearsideAxle3;

        return $this;
    }

    /**
     * Get effortNearsideAxle3
     *
     * @return integer
     */
    public function getEffortNearsideAxle3()
    {
        return $this->effortNearsideAxle3;
    }

    /**
     * Set effortOffsideAxle3
     *
     * @param integer $effortOffsideAxle3
     * @return BrakeTestResultServiceBrakeData
     */
    public function setEffortOffsideAxle3($effortOffsideAxle3)
    {
        $this->effortOffsideAxle3 = $effortOffsideAxle3;

        return $this;
    }

    /**
     * Get effortOffsideAxle3
     *
     * @return integer
     */
    public function getEffortOffsideAxle3()
    {
        return $this->effortOffsideAxle3;
    }

    /**
     * Set effortSingle
     *
     * @param integer $effortSingle
     * @return BrakeTestResultServiceBrakeData
     */
    public function setEffortSingle($effortSingle)
    {
        $this->effortSingle = $effortSingle;

        return $this;
    }

    /**
     * Get effortSingle
     *
     * @return integer
     */
    public function getEffortSingle()
    {
        return $this->effortSingle;
    }

    /**
     * Set lockNearsideAxle1
     *
     * @param boolean $lockNearsideAxle1
     * @return BrakeTestResultServiceBrakeData
     */
    public function setLockNearsideAxle1($lockNearsideAxle1)
    {
        $this->lockNearsideAxle1 = $lockNearsideAxle1;

        return $this;
    }

    /**
     * Get lockNearsideAxle1
     *
     * @return boolean
     */
    public function getLockNearsideAxle1()
    {
        return $this->lockNearsideAxle1;
    }

    /**
     * Set lockOffsideAxle1
     *
     * @param boolean $lockOffsideAxle1
     * @return BrakeTestResultServiceBrakeData
     */
    public function setLockOffsideAxle1($lockOffsideAxle1)
    {
        $this->lockOffsideAxle1 = $lockOffsideAxle1;

        return $this;
    }

    /**
     * Get lockOffsideAxle1
     *
     * @return boolean
     */
    public function getLockOffsideAxle1()
    {
        return $this->lockOffsideAxle1;
    }

    /**
     * Set lockNearsideAxle2
     *
     * @param boolean $lockNearsideAxle2
     * @return BrakeTestResultServiceBrakeData
     */
    public function setLockNearsideAxle2($lockNearsideAxle2)
    {
        $this->lockNearsideAxle2 = $lockNearsideAxle2;

        return $this;
    }

    /**
     * Get lockNearsideAxle2
     *
     * @return boolean
     */
    public function getLockNearsideAxle2()
    {
        return $this->lockNearsideAxle2;
    }

    /**
     * Set lockOffsideAxle2
     *
     * @param boolean $lockOffsideAxle2
     * @return BrakeTestResultServiceBrakeData
     */
    public function setLockOffsideAxle2($lockOffsideAxle2)
    {
        $this->lockOffsideAxle2 = $lockOffsideAxle2;

        return $this;
    }

    /**
     * Get lockOffsideAxle2
     *
     * @return boolean
     */
    public function getLockOffsideAxle2()
    {
        return $this->lockOffsideAxle2;
    }

    /**
     * Set lockNearsideAxle3
     *
     * @param boolean $lockNearsideAxle3
     * @return BrakeTestResultServiceBrakeData
     */
    public function setLockNearsideAxle3($lockNearsideAxle3)
    {
        $this->lockNearsideAxle3 = $lockNearsideAxle3;

        return $this;
    }

    /**
     * Get lockNearsideAxle3
     *
     * @return boolean
     */
    public function getLockNearsideAxle3()
    {
        return $this->lockNearsideAxle3;
    }

    /**
     * Set lockOffsideAxle3
     *
     * @param boolean $lockOffsideAxle3
     * @return BrakeTestResultServiceBrakeData
     */
    public function setLockOffsideAxle3($lockOffsideAxle3)
    {
        $this->lockOffsideAxle3 = $lockOffsideAxle3;

        return $this;
    }

    /**
     * Get lockOffsideAxle3
     *
     * @return boolean
     */
    public function getLockOffsideAxle3()
    {
        return $this->lockOffsideAxle3;
    }

    /**
     * Set lockSingle
     *
     * @param boolean $lockSingle
     * @return BrakeTestResultServiceBrakeData
     */
    public function setLockSingle($lockSingle)
    {
        $this->lockSingle = $lockSingle;

        return $this;
    }

    /**
     * Get lockSingle
     *
     * @return boolean
     */
    public function getLockSingle()
    {
        return $this->lockSingle;
    }

    /**
     * Set imbalanceAxle1
     *
     * @param integer $imbalanceAxle1
     * @return BrakeTestResultServiceBrakeData
     */
    public function setImbalanceAxle1($imbalanceAxle1)
    {
        $this->imbalanceAxle1 = $imbalanceAxle1;

        return $this;
    }

    /**
     * Get imbalanceAxle1
     *
     * @return integer
     */
    public function getImbalanceAxle1()
    {
        return $this->imbalanceAxle1;
    }

    /**
     * Set imbalanceAxle2
     *
     * @param integer $imbalanceAxle2
     * @return BrakeTestResultServiceBrakeData
     */
    public function setImbalanceAxle2($imbalanceAxle2)
    {
        $this->imbalanceAxle2 = $imbalanceAxle2;

        return $this;
    }

    /**
     * Get imbalanceAxle2
     *
     * @return integer
     */
    public function getImbalanceAxle2()
    {
        return $this->imbalanceAxle2;
    }

    /**
     * Set imbalanceAxle3
     *
     * @param integer $imbalanceAxle3
     * @return BrakeTestResultServiceBrakeData
     */
    public function setImbalanceAxle3($imbalanceAxle3)
    {
        $this->imbalanceAxle3 = $imbalanceAxle3;

        return $this;
    }

    /**
     * Get imbalanceAxle3
     *
     * @return integer
     */
    public function getImbalanceAxle3()
    {
        return $this->imbalanceAxle3;
    }

    /**
     * Set imbalancePass
     *
     * @param boolean $imbalancePass
     * @return BrakeTestResultServiceBrakeData
     */
    public function setImbalancePass($imbalancePass)
    {
        $this->imbalancePass = $imbalancePass;

        return $this;
    }

    /**
     * Get imbalancePass
     *
     * @return boolean
     */
    public function getImbalancePass()
    {
        return $this->imbalancePass;
    }

    /**
     * @param $axleNumber
     * @return bool | null
     */
    public function getImbalancePassForAxle($axleNumber)
    {
        return isset($this->imbalancePassForAxle[$axleNumber]) ? $this->imbalancePassForAxle[$axleNumber] : null;
    }

    /**
     * @param $axleNumber
     * @param $pass
     * @return BrakeTestResultServiceBrakeData
     */
    public function setImbalancePassForAxle($axleNumber, $pass)
    {
        $this->imbalancePassForAxle[$axleNumber] = $pass;
        return $this;
    }
}
