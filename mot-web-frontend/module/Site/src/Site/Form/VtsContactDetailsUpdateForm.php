<?php
namespace Site\Form;

use DvsaClient\ViewModel\AbstractFormModel;
use DvsaClient\ViewModel\EmailFormModel;
use DvsaClient\ViewModel\PhoneFormModel;
use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\Enum\PhoneContactTypeCode;
use DvsaCommon\Enum\SiteContactTypeCode;
use Zend\Stdlib\Parameters;

class VtsContactDetailsUpdateForm extends AbstractFormModel
{
    /** @var  VehicleTestingStationDto */
    private $vtsDto;
    /** @var  SiteContactDto */
    private $busContact;
    /** @var  EmailFormModel */
    private $busEmailModel;
    /** @var  PhoneFormModel */
    private $busPhoneModel;

    public function __construct()
    {
        $this->busEmailModel = new EmailFormModel();
        $this->busEmailModel->setIsPrimary(true);

        $this->busPhoneModel = new PhoneFormModel();
        $this->busPhoneModel
            ->setIsPrimary(true)
            ->setType(PhoneContactTypeCode::BUSINESS);
    }

    /**
     * @return $this
     */
    public function fromPost(Parameters $postData)
    {
        /** @var Parameters $contactData */
        $contactData = new Parameters($postData->get(SiteContactTypeCode::BUSINESS));

        $this->getBusEmailModel()->fromPost($contactData);
        $this->getBusPhoneModel()->fromPost($contactData);

        return $this;
    }

    public function toDto()
    {
        $dto = $this->busContact;
        $dto->setAddress(null);

        //  set email
        $dto->setEmails([$this->getBusEmailModel()->toDto()]);

        //  set phone
        $dto->setPhones([$this->getBusPhoneModel()->toDto()]);

        return $dto;
    }

    /**
     * API data structure is nested, while the form uses flat structure of fields.
     */
    public function fromDto(VehicleTestingStationDto $vtsDto)
    {
        $this->vtsDto = $vtsDto;

        /** @var SiteContactDto $busContact */
        $this->busContact = $this->vtsDto->getContactByType(SiteContactTypeCode::BUSINESS);

        if ($this->busContact instanceof SiteContactDto) {
            $this->getBusEmailModel()->fromDto($this->busContact->getPrimaryEmail());
            $this->getBusPhoneModel()->fromDto($this->busContact->getPrimaryPhone());
        }

        return $this;
    }

    public function getVtsDto()
    {
        return $this->vtsDto;
    }

    public function getBusEmailModel()
    {
        return $this->busEmailModel;
    }

    public function getBusPhoneModel()
    {
        return $this->busPhoneModel;
    }

    public function isValid()
    {
		$this->resetErrors();
        $isEmailValid = $this->getBusEmailModel()->isValid(SiteContactTypeCode::BUSINESS);
        $isPhoneValid = $this->getBusPhoneModel()->isValid(SiteContactTypeCode::BUSINESS);

        return $isEmailValid && $isPhoneValid;
    }
}
