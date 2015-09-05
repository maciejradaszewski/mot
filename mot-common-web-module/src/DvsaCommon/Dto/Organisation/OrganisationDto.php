<?php

namespace DvsaCommon\Dto\Organisation;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;
use DvsaCommon\Dto\Contact\ContactDto;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\Utility\ArrayUtils;

/**
 * Class OrganisationDto
 *
 * @package DvsaCommon\Dto\Organisation
 */
class OrganisationDto extends AbstractDataTransferObject
{
    use CommonIdentityDtoTrait;

    private $name;
    private $tradingAs;
    private $registeredCompanyNumber;
    private $organisationType;
    private $companyType;
    /** @var  OrganisationContactDto[] */
    private $contacts = [];
    /** @var AuthorisedExaminerAuthorisationDto */
    private $authorisedExaminerAuthorisation;
    private $slotBalance;
    private $slotWarning;
    private $dataMayBeDisclosed;
    private $isValidateOnly = false;


    /**
     * @param AuthorisedExaminerAuthorisationDto $authorisedExaminerAuthorisation
     *
     * @return OrganisationDto
     */
    public function setAuthorisedExaminerAuthorisation($authorisedExaminerAuthorisation)
    {
        $this->authorisedExaminerAuthorisation = $authorisedExaminerAuthorisation;
        return $this;
    }

    public function getAuthorisedExaminerAuthorisation()
    {
        return $this->authorisedExaminerAuthorisation;
    }

    /**
     * @param string $name
     *
     * @return OrganisationDto
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $tradingAs
     *
     * @return OrganisationDto
     */
    public function setTradingAs($tradingAs)
    {
        $this->tradingAs = $tradingAs;
        return $this;
    }

    public function getTradingAs()
    {
        return $this->tradingAs;
    }

    /**
     * @param int $organisationType
     *
     * @return OrganisationDto
     */
    public function setOrganisationType($organisationType)
    {
        $this->organisationType = $organisationType;
        return $this;
    }

    public function getOrganisationType()
    {
        return $this->organisationType;
    }

    /**
     * @return mixed
     */
    public function getCompanyType()
    {
        return $this->companyType;
    }

    /**
     * @param int $companyType
     *
     * @return $this
     */
    public function setCompanyType($companyType)
    {
        $this->companyType = $companyType;

        return $this;
    }



    /**
     * @param string $registeredCompanyNumber
     *
     * @return OrganisationDto
     */
    public function setRegisteredCompanyNumber($registeredCompanyNumber)
    {
        $this->registeredCompanyNumber = $registeredCompanyNumber;
        return $this;
    }

    public function getRegisteredCompanyNumber()
    {
        return $this->registeredCompanyNumber;
    }

    /**
     * @param OrganisationContactDto[] $contacts
     *
     * @return $this
     */
    public function setContacts($contacts)
    {
        $this->contacts = $contacts;
        return $this;
    }

    /**
     * @return OrganisationContactDto[]
     */
    public function getContacts()
    {
        return $this->contacts;
    }

    /**
     * @return ContactDto|null
     */
    public function getRegisteredCompanyContactDetail()
    {
        return $this->getContactByType(OrganisationContactTypeCode::REGISTERED_COMPANY);
    }

    /**
     * @param $type
     *
     * @return OrganisationContactDto|null
     */
    public function getContactByType($type)
    {
        return ArrayUtils::firstOrNull(
            $this->getContacts(),
            function (OrganisationContactDto $contact) use ($type) {
                return $contact->getType() == $type;
            }
        );
    }

    /**
     * @return ContactDto|null
     */
    public function getCorrespondenceContactDetail()
    {
        return $this->getContactByType(OrganisationContactTypeCode::CORRESPONDENCE);
    }

    /**
     * @return OrganisationDto
     */
    public function setSlotBalance($slotBalance)
    {
        $this->slotBalance = $slotBalance;
        return $this;
    }

    /**
     * @return int
     */
    public function getSlotBalance()
    {
        return $this->slotBalance;
    }

    /**
     * @return mixed
     */
    public function getSlotWarning()
    {
        return $this->slotWarning;
    }

    /**
     * @param mixed $slotWarning
     * @return $this
     */
    public function setSlotWarning($slotWarning)
    {
        $this->slotWarning = $slotWarning;
        return $this;
    }

    /**
     * @return bool
     */
    public function getDataMayBeDisclosed()
    {
        return $this->dataMayBeDisclosed;
    }

    /**
     * @param bool $dataMayBeDisclosed
     *
     * @return $this
     */
    public function setDataMayBeDisclosed($dataMayBeDisclosed)
    {
        $this->dataMayBeDisclosed = $dataMayBeDisclosed;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isValidateOnly()
    {
        return $this->isValidateOnly;
    }

    /**
     * @param boolean $isValidateOnly
     * @return $this
     */
    public function setIsValidateOnly($isValidateOnly)
    {
        $this->isValidateOnly = $isValidateOnly;
        return $this;
    }
}
