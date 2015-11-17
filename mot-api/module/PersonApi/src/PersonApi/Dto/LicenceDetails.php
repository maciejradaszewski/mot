<?php

namespace PersonApi\Dto;

use Doctrine\ORM\EntityManager;
use DvsaEntities\Entity\Licence;
use DvsaEntities\Entity\LicenceType;
use Zend\Stdlib\Hydrator;

class LicenceDetails
{
    /**
     * @var int $id
     */
    private $id;

    /**
     * @var String $licenceNumber
     */
    private $licenceNumber;

    /**
     * @var String $licenceCountry
     */
    private $licenceCountry;

    /**
     * @var \DateTime $validFrom
     */
    private $validFrom;

    /**
     * @var \DateTime $expiryDate
     */
    private $expiryDate;

    /**
     * @var LicenceType $licenceType
     */
    private $licenceType;

    /**
     * @param Licence $licence
     */
    public function __construct(Licence $licence)
    {
        $this->id = $licence->getId();
        $this->licenceNumber = $licence->getLicenceNumber();
        if ($licence->hasCountry()) {
            $this->licenceCountry = $licence->getCountry();
        } else {
            $this->licenceCountry = null;
        }

        $this->validFrom = $licence->getValidFrom();
        $this->expiryDate = $licence->getExpiryDate();
        $this->licenceType = $licence->getLicenceType();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $hydrator = new Hydrator\ClassMethods(false);

        return $hydrator->extract($this);
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return String
     */
    public function getLicenceNumber()
    {
        return $this->licenceNumber;
    }

    /**
     * @param String $licenceNumber
     *
     * @return $this
     */
    public function setLicenceNumber($licenceNumber)
    {
        $this->licenceNumber = $licenceNumber;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getValidFrom()
    {
        return $this->validFrom;
    }

    /**
     * @param \DateTime $validFrom
     *
     * @return $this
     */
    public function setValidFrom($validFrom)
    {
        $this->validFrom = $validFrom;

        return $this;
    }

    /**
     * @return String
     */
    public function getLicenceCountry()
    {
        return $this->licenceCountry;
    }

    /**
     * @param String $licenceCountry
     *
     * @return $this
     */
    public function setLicenceCountry($licenceCountry)
    {
        $this->licenceCountry = $licenceCountry;

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
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    /**
     * @return LicenceType
     */
    public function getLicenceType()
    {
        return $this->licenceType;
    }

    /**
     * @param LicenceType $licenceType
     *
     * @return $this
     */
    public function setLicenceType($licenceType)
    {
        $this->licenceType = $licenceType;

        return $this;
    }

}
