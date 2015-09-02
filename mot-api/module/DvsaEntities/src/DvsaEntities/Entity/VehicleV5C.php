<?php

namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="vehicle_v5c", options={"collate"="utf8_general_ci", "charset"="utf8", "engine"="InnoDB"})
 * @ORM\Entity(repositoryClass="DvsaEntities\Repository\VehicleV5CRepository")
 */
class VehicleV5C extends Entity
{
    use CommonIdentityTrait;

    /**
     * @var Vehicle
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\Vehicle", inversedBy="vehicleV5Cs")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="vehicle_id", referencedColumnName="id")
     * })
     */
    private $vehicle;

    /**
     * @var string
     *
     * @ORM\Column(name="v5c_ref", type="string", length=11, nullable=false)
     */
    private $v5cRef;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="first_seen", type="date", nullable=false)
     */
    private $firstSeen;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_seen", type="date", nullable=true)
     */
    private $lastSeen;

    /**
     * @var string
     *
     * @ORM\Column(name="mot1_legacy_id", type="string", length=11, nullable=false)
     */
    private $mot1LegacyId;


    /**
     * @return \DateTime
     */
    public function getFirstSeen()
    {
        return $this->firstSeen;
    }

    /**
     * @param \DateTime $firstSeen
     * @return VehicleV5C $this
     */
    public function setFirstSeen($firstSeen)
    {
        $this->firstSeen = $firstSeen;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastSeen()
    {
        return $this->lastSeen;
    }

    /**
     * @param \DateTime $lastSeen
     * @return VehicleV5C $this
     */
    public function setLastSeen($lastSeen)
    {
        $this->lastSeen = $lastSeen;
        return $this;
    }

    /**
     * @return string
     */
    public function getMot1LegacyId()
    {
        return $this->mot1LegacyId;
    }

    /**
     * @param string $mot1LegacyId
     * @return VehicleV5C $this
     */
    public function setMot1LegacyId($mot1LegacyId)
    {
        $this->mot1LegacyId = $mot1LegacyId;
        return $this;
    }

    /**
     * @return string
     */
    public function getV5cRef()
    {
        return $this->v5cRef;
    }

    /**
     * @param string $v5cRef
     * @return VehicleV5C $this
     */
    public function setV5cRef($v5cRef)
    {
        $this->v5cRef = $v5cRef;
        return $this;
    }

    /**
     * @return Vehicle
     */
    public function getVehicle()
    {
        return $this->vehicle;
    }

    /**
     * @param Vehicle $vehicle
     * @return VehicleV5C $this
     */
    public function setVehicle($vehicle)
    {
        $this->vehicle = $vehicle;
        return $this;
    }
}
