<?php

namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;
use DvsaEntities\Entity\CountryOfRegistration;

/**
 * Licence
 *
 * @ORM\Table(name="licence")
 * @ORM\Entity
 */
class Licence extends Entity
{
    use CommonIdentityTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="licence_number", type="string", length=45, nullable=false)
     */
    private $licenceNumber;

    /**
     * @var CountryOfRegistration
     *
     * @ORM\ManyToOne(targetEntity="CountryOfRegistration")
     * @ORM\JoinColumn(name="country_lookup_id", referencedColumnName="id")
     */
    private $country;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="valid_from", type="datetime", nullable=true)
     */
    private $validFrom;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiry_date", type="datetime", nullable=true)
     */
    private $expiryDate;

    /**
     * @var LicenceType
     *
     * @ORM\ManyToOne(targetEntity="LicenceType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="licence_type_id", referencedColumnName="id")
     * })
     */
    private $licenceType;

    /**
     * @param CountryOfRegistration $country
     *
     * @return Licence
     */
    public function setCountry(CountryOfRegistration $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @param \DateTime $expiryDate
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;
    }

    /**
     * @param string $licenceNumber
     *
     * @return Licence
     */
    public function setLicenceNumber($licenceNumber)
    {
        $this->licenceNumber = $licenceNumber;

        return $this;
    }

    /**
     * @param LicenceType $licenceType
     */
    public function setLicenceType($licenceType)
    {
        $this->licenceType = $licenceType;
    }

    /**
     * @param \DateTime $validFrom
     */
    public function setValidFrom($validFrom)
    {
        $this->validFrom = $validFrom;
    }

    /**
     * @return CountryOfRegistration
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @return \DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * @return string
     */
    public function getLicenceNumber()
    {
        return $this->licenceNumber;
    }

    /**
     * @return \DvsaEntities\Entity\LicenceType
     */
    public function getLicenceType()
    {
        return $this->licenceType;
    }

    /**
     * @return \DateTime
     */
    public function getValidFrom()
    {
        return $this->validFrom;
    }
}
