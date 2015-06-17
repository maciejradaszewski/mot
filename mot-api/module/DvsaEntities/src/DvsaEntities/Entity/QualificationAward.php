<?php

namespace DvsaEntities\Entity;

use Doctrine\ORM\Mapping as ORM;
use DvsaEntities\EntityTrait\CommonIdentityTrait;

/**
 * QualificationAward
 *
 * @ORM\Table(name="qualification_award", uniqueConstraints={@ORM\UniqueConstraint(name="id_UNIQUE", columns={"id"})}, indexes={@ORM\Index(name="fk_qualification_award_1", columns={"person_id"}), @ORM\Index(name="fk_qualification_award_2", columns={"qualification_id"}), @ORM\Index(name="fk_qualification_award_3", columns={"created_by"}), @ORM\Index(name="fk_qualification_award_6", columns={"verified_by"})})
 * @ORM\Entity
 */
class QualificationAward extends Entity
{
    use CommonIdentityTrait;

    const ENTITY_NAME = 'QualificationAward';

    /**
     * @var string
     *
     * @ORM\Column(name="certificate_number", type="string", length=50, nullable=true)
     */
    private $certificateNumber;

    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumn(name="country_lookup_id", referencedColumnName="id")
     */
    private $country;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="awarded_on", type="datetime", nullable=true)
     */
    private $awardedOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="verified_on", type="datetime", nullable=true)
     */
    private $verifiedOn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiry_date", type="datetime", nullable=true)
     */
    private $expiryDate;

    /**
     * @var integer
     *
     * @ORM\Column(name="status_id", type="smallint", nullable=true)
     */
    private $statusId;

    /**
     * @var \DvsaEntities\Entity\Person
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\Person")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="person_id", referencedColumnName="id")
     * })
     */
    private $person;

    /**
     * @var \DvsaEntities\Entity\Qualification
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\Qualification")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="qualification_id", referencedColumnName="id")
     * })
     */
    private $qualification;

    /**
     * @var \DvsaEntities\Entity\Person
     *
     * @ORM\ManyToOne(targetEntity="DvsaEntities\Entity\Person")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="verified_by", referencedColumnName="id")
     * })
     */
    private $verifiedBy;

    /**
     * Set certificateNumber
     *
     * @param string $certificateNumber
     *
     * @return QualificationAward
     */
    public function setCertificateNumber($certificateNumber)
    {
        $this->certificateNumber = $certificateNumber;

        return $this;
    }

    /**
     * Get certificateNumber
     *
     * @return string
     */
    public function getCertificateNumber()
    {
        return $this->certificateNumber;
    }

    /**
     * @param Country $country
     *
     * @return MotTest
     */
    public function setCountry(Country $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * @return Country
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set awardedOn
     *
     * @param \DateTime $awardedOn
     *
     * @return QualificationAward
     */
    public function setAwardedOn($awardedOn)
    {
        $this->awardedOn = $awardedOn;

        return $this;
    }

    /**
     * Get awardedOn
     *
     * @return \DateTime
     */
    public function getAwardedOn()
    {
        return $this->awardedOn;
    }

    /**
     * Set verifiedOn
     *
     * @param \DateTime $verifiedOn
     *
     * @return QualificationAward
     */
    public function setVerifiedOn($verifiedOn)
    {
        $this->verifiedOn = $verifiedOn;

        return $this;
    }

    /**
     * Get verifiedOn
     *
     * @return \DateTime
     */
    public function getVerifiedOn()
    {
        return $this->verifiedOn;
    }

    /**
     * Set expiryDate
     *
     * @param \DateTime $expiryDate
     *
     * @return QualificationAward
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    /**
     * Get expiryDate
     *
     * @return \DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * Set statusId
     *
     * @param integer $statusId
     *
     * @return QualificationAward
     */
    public function setStatusId($statusId)
    {
        $this->statusId = $statusId;

        return $this;
    }

    /**
     * Get statusId
     *
     * @return integer
     */
    public function getStatusId()
    {
        return $this->statusId;
    }

    /**
     * Set person
     *
     * @param \DvsaEntities\Entity\Person $person
     *
     * @return QualificationAward
     */
    public function setPerson(\DvsaEntities\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \DvsaEntities\Entity\Person
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set qualification
     *
     * @param \DvsaEntities\Entity\Qualification $qualification
     *
     * @return QualificationAward
     */
    public function setQualification(\DvsaEntities\Entity\Qualification $qualification = null)
    {
        $this->qualification = $qualification;

        return $this;
    }

    /**
     * Get qualification
     *
     * @return \DvsaEntities\Entity\Qualification
     */
    public function getQualification()
    {
        return $this->qualification;
    }

    /**
     * Set verifiedBy
     *
     * @param \DvsaEntities\Entity\Person $verifiedBy
     *
     * @return QualificationAward
     */
    public function setVerifiedBy(\DvsaEntities\Entity\Person $verifiedBy = null)
    {
        $this->verifiedBy = $verifiedBy;

        return $this;
    }

    /**
     * Get verifiedBy
     *
     * @return \DvsaEntities\Entity\Person
     */
    public function getVerifiedBy()
    {
        return $this->verifiedBy;
    }
}
