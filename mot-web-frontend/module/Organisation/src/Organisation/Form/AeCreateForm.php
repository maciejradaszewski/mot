<?php

namespace Organisation\Form;

use DvsaClient\ViewModel\AbstractFormModel;
use DvsaClient\ViewModel\ContactDetailFormModel;
use DvsaCommon\Dto\Contact\ContactDto;
use DvsaCommon\Dto\Organisation\OrganisationContactDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use Zend\Stdlib\Parameters;

/**
 * Representation of AE creation form.
 */
class AeCreateForm extends AbstractFormModel
{
    const FIELD_IS_CORR_DETAILS_THE_SAME = 'isCorrDetailsSame';
    const FIELD_NAME = 'organisationName';
    const FIELD_TRADING_AS = 'tradingAs';
    const FIELD_COMPANY_TYPE = 'companyType';
    const FIELD_REG_NR = 'registeredCompanyNumber';
    const FIELD_AO_NR = 'assignedAreaOffice';

    const ERR_NAME_REQUIRE = 'Organisation name require';

    private $organisationName;
    private $tradingAs;
    private $companyType;
    private $registeredCompanyNumber;
    private $areaOfficeNumber;

    private $isCorrDetailsTheSame = true;

    /**
     * @var ContactDetailFormModel
     */
    protected $regContactModel;
    /**
     * @var ContactDetailFormModel
     */
    protected $corrContactModel;

    private $companyTypes = [];
    private $areaOfficeOptions;

    private $cancelUrl;

    public function __construct(OrganisationDto $org = null)
    {
        $this->regContactModel = new ContactDetailFormModel(OrganisationContactTypeCode::REGISTERED_COMPANY);
        $this->corrContactModel = new ContactDetailFormModel(OrganisationContactTypeCode::CORRESPONDENCE);

        $this->fromDto($org);
    }

    public function fromPost(Parameters $data)
    {
        $this
            ->setName($data->get(self::FIELD_NAME))
            ->setTradingAs($data->get(self::FIELD_TRADING_AS))
            ->setCompanyType($data->get(self::FIELD_COMPANY_TYPE))
            ->setRegisteredCompanyNumber($data->get(self::FIELD_REG_NR))
            ->setAreaOfficeNumber($data->get(self::FIELD_AO_NR));

        $this->setIsCorrDetailsTheSame((bool) $data->get(self::FIELD_IS_CORR_DETAILS_THE_SAME) === true);

        $this->regContactModel->fromPost($data);
        $this->corrContactModel->fromPost($data);

        return $this;
    }

    public function fromDto(OrganisationDto $org = null)
    {
        if ($org === null) {
            return $this;
        }

        $this->organisationName = $org->getName();
        $this->tradingAs = $org->getTradingAs();
        $this->companyType = $org->getCompanyType();
        $this->registeredCompanyNumber = $org->getRegisteredCompanyNumber();
        $this->setAreaOfficeNumber($org->getAreaOfficeSite());

        $busContact = $org->getContactByType(OrganisationContactTypeCode::REGISTERED_COMPANY);
        $corrContact = $org->getContactByType(OrganisationContactTypeCode::CORRESPONDENCE);

        if (!$corrContact instanceof OrganisationContactDto
            || ContactDto::isEquals($busContact, $corrContact)
        ) {
            $this->isCorrDetailsTheSame = true;

            $corrContact = $busContact;
        } else {
            $this->isCorrDetailsTheSame = false;
        }

        $this->regContactModel->fromDto($busContact);
        $this->corrContactModel->fromDto($corrContact);

        return $this;
    }

    public function toDto()
    {
        //  logical block :: fill contacts
        $regContact = $this->regContactModel->toDto(new OrganisationContactDto());

        if ($this->isCorrDetailsTheSame === true) {
            $corrContact = clone $regContact;
            $corrContact->setType(OrganisationContactTypeCode::CORRESPONDENCE);
        } else {
            $corrContact = $this->corrContactModel->toDto(new OrganisationContactDto());
        }

        $contacts = [
            $regContact,
            $corrContact,
        ];

        //  logical block :: assemble dto
        $aeDto = new OrganisationDto();
        $aeDto
            ->setName($this->getName())
            ->setRegisteredCompanyNumber($this->getRegisteredCompanyNumber())
            ->setCompanyType($this->getCompanyType())
            ->setTradingAs($this->getTradingAs())
            ->setContacts($contacts)
            ->setAreaOfficeSite($this->getAreaOfficeNumber());

        return $aeDto;
    }

    public function isValid()
    {
        $isValid = $this->regContactModel->isValid();

        if ($this->isCorrDetailsTheSame === false) {
            $isValid = $this->corrContactModel->isValid() && $isValid;
        }

        return $isValid;
    }

    public function getName()
    {
        return $this->organisationName;
    }

    /**
     * @return $this
     */
    private function setName($organisationName)
    {
        $this->organisationName = $organisationName;
        return $this;
    }

    public function getTradingAs()
    {
        return $this->tradingAs;
    }

    /**
     * @return $this
     */
    private function setTradingAs($tradingAs)
    {
        $this->tradingAs = $tradingAs;
        return $this;
    }


    public function getCompanyType()
    {
        return $this->companyType;
    }

    /**
     * @return $this
     */
    private function setCompanyType($companyType)
    {
        $this->companyType = $companyType;
        return $this;
    }

    public function getRegisteredCompanyNumber()
    {
        return $this->registeredCompanyNumber;
    }

    /**
     * @return $this
     */
    private function setRegisteredCompanyNumber($registeredCompanyNumber)
    {
        $this->registeredCompanyNumber = $registeredCompanyNumber;
        return $this;
    }

    public function getAreaOfficeNumber()
    {
        return $this->areaOfficeNumber;
    }

    /**
     * @return $this
     */
    private function setAreaOfficeNumber($areaOfficeNumber)
    {
        $this->areaOfficeNumber = $areaOfficeNumber;
        return $this;
    }


    /**
     * @return boolean
     */
    public function isCorrDetailsTheSame()
    {
        return (bool) $this->isCorrDetailsTheSame;
    }

    /**
     * @return $this
     */
    private function setIsCorrDetailsTheSame($isTheSame)
    {
        $this->isCorrDetailsTheSame = $isTheSame;
        return $this;
    }

    /**
     * Answers a list of the valid Area Office indices and their internal codes.
     *
     * @return array
     */
    public function getAreaOfficeOptions()
    {
        return $this->areaOfficeOptions;
    }

    /**
     * @return $this
     */
    public function setAreaOfficeOptions(array $areaOfficeOptions)
    {
        $this->areaOfficeOptions = $areaOfficeOptions;
        return $this;
    }


    /**
     * @return \DvsaCommon\Enum\CompanyTypeName[]
     */
    public function getCompanyTypes()
    {
        return $this->companyTypes;
    }

    /**
     * @param array $companyTypes
     * @return $this
     */
    public function setCompanyTypes(array $companyTypes)
    {
        $this->companyTypes = $companyTypes;
        return $this;
    }


    /**
     * @return ContactDetailFormModel
     */
    public function getBusContactModel()
    {
        return $this->regContactModel;
    }

    /**
     * @return ContactDetailFormModel
     */
    public function getCorrContactModel()
    {
        return $this->corrContactModel;
    }


    public function getCancelUrl()
    {
        return $this->cancelUrl;
    }

    /**
     * @param $url
     * @return $this
     */
    public function setCancelUrl($url)
    {
        $this->cancelUrl = $url;
        return $this;
    }
}
