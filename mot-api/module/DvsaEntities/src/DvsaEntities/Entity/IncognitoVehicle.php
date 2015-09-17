<?php

namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;

/**
 * Incognito Vehicle.
 *
 * @ORM\Table(
 *  name="incognito_vehicle",
 *  indexes={
 *      @ORM\Index(name="fk_incognito_vehicle_site_id", columns={"site_id"}),
 *      @ORM\Index(name="fk_incognito_vehicle_vehicle_id", columns={"vehicle_id"}),
 *      @ORM\Index(name="fk_incognito_vehicle_person_id", columns={"person_id"}),
 *      @ORM\Index(name="fk_incognito_vehicle_person_created", columns={"created_by"}),
 *      @ORM\Index(name="fk_incognito_vehicle_person_last_updated_by", columns={"last_updated_by"})
 *  }
 * )
 * @ORM\Entity(repositoryClass="DvsaEntities\Repository\IncognitoVehicleRepository")
 */
class IncognitoVehicle extends Entity
{
    use CommonIdentityTrait;

    /**
     * Vehicle Entity for the vehicle assigned to be a mystery shopper vehicle.
     *
     * @var Vehicle
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\Vehicle")
     * @ORM\JoinColumn(name="vehicle_id", referencedColumnName="id")
     */
    private $vehicle;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_date", type="datetime", nullable=true)
     */
    private $startDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_date", type="datetime", nullable=true)
     */
    private $endDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="test_date", type="date", nullable=true)
     */
    private $testDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiry_date", type="date", nullable=true)
     */
    private $expiryDate;

    /**
     * Area Office for the mystery shopper vehicle.
     *
     * @var Site
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\Site")
     * @ORM\JoinColumn(name="site_id", referencedColumnName="id")
     */
    private $site;

    /**
     * VE or AO1 who set up the vehicle as a mystery shopper vehicle.
     *
     * @var Person
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\Person")
     * @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     */
    private $person;

    /**
     * @return Vehicle
     */
    public function getVehicle()
    {
        return $this->vehicle;
    }

    /**
     * @param $vehicle
     *
     * @return $this
     */
    public function setVehicle(Vehicle $vehicle)
    {
        $this->vehicle = $vehicle;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param \DateTime $startDate
     *
     * @return $this
     */
    public function setStartDate(\DateTime $startDate = null)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param \DateTime $endDate
     *
     * @return $this
     */
    public function setEndDate(\DateTime $endDate = null)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTestDate()
    {
        return $this->testDate;
    }

    /**
     * @param \DateTime $testDate
     *
     * @return $this
     */
    public function setTestDate(\DateTime $testDate = null)
    {
        $this->testDate = $testDate;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * @param \DateTime $expiryDate
     *
     * @return $this
     */
    public function setExpiryDate(\DateTime $expiryDate = null)
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    /**
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param Site
     *
     * @return $this
     */
    public function setSite(Site $site = null)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param Person
     *
     * @return $this
     */
    public function setPerson(Person $person = null)
    {
        $this->person = $person;

        return $this;
    }
}
