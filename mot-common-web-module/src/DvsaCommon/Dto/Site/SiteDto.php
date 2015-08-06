<?php

namespace DvsaCommon\Dto\Site;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\Common\CommentDto;
use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Utility\ArrayUtils;

/**
 * Class SiteDto
 *
 * @package DvsaCommon\Dto\Site
 */
class SiteDto extends AbstractDataTransferObject
{
    use CommonIdentityDtoTrait;

    /** @var  string */
    private $siteNumber;
    /** @var  string */
    private $name;

    /** @var  SiteContactDto[]  */
    private $contacts = [];

    /** @var  OrganisationDto */
    private $organisation;

    /** @var  SiteAssessmentDto */
    private $assessment;

    /** @var  string */
    private $latitude;
    /** @var  string */
    private $longitude;

    /** @var boolean */
    private $isDualLanguage = false;
    /** @var boolean */
    private $isScottishBankHoliday = false;

    /** @var  CommentDto[] */
    private $comments;

    /** @var  String */
    private $typeCode;


    public function getSiteNumber()
    {
        return $this->siteNumber;
    }

    /**
     * @return $this
     */
    public function setSiteNumber($siteNumber)
    {
        $this->siteNumber = $siteNumber;
        return $this;
    }


    public function getName()
    {
        return $this->name;
    }

    /**
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return SiteContactDto[]
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @param SiteContactDto[] $contacts
     *
     * @return $this
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;
        return $this;
    }

    /**
     * @param SiteContactDto $contactDto
     *
     * @return $this
     */
    public function addContact($contactDto)
    {
        $this->contacts[] = $contactDto;
        return $this;
    }

    /**
     * @param $type
     *
     * @return SiteContactDto|null
     */
    public function getContactByType($type)
    {
        return ArrayUtils::firstOrNull(
            $this->getContacts(), function (SiteContactDto $siteContact) use ($type) {
                return $siteContact->getType() == $type;
            }
        );
    }

    /**
     * @return OrganisationDto
     */
    public function getOrganisation()
    {
        return $this->organisation;
    }

    /**
     * @param OrganisationDto $organisation
     *
     * @return $this
     */
    public function setOrganisation($organisation)
    {
        $this->organisation = $organisation;
        return $this;
    }

    /**
     * @return SiteAssessmentDto
     */
    public function getAssessment()
    {
        return $this->assessment;
    }

    /**
     * @param SiteAssessmentDto $assessment
     *
     * @return $this
     */
    public function setAssessment($assessment)
    {
        $this->assessment = $assessment;
        return $this;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return $this
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @return $this
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isDualLanguage()
    {
        return $this->isDualLanguage;
    }

    /**
     * @param boolean $isDualLanguage
     *
     * @return $this
     */
    public function setIsDualLanguage($isDualLanguage)
    {
        $this->isDualLanguage = $isDualLanguage;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isScottishBankHoliday()
    {
        return $this->isScottishBankHoliday;
    }

    /**
     * @param boolean $isScottishBankHoliday
     *
     * @return $this
     */
    public function setIsScottishBankHoliday($isScottishBankHoliday)
    {
        $this->isScottishBankHoliday = $isScottishBankHoliday;
        return $this;
    }

    /**
     * @return CommentDto[]
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * @param CommentDto[] $comments
     *
     * @return $this
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
        return $this;
    }

    public function getType()
    {
        return $this->typeCode;
    }

    /**
     * @param integer $typeId
     *
     * @return $this
     */
    public function setType($typeCode)
    {
        $this->typeCode = $typeCode;
        return $this;
    }
}
