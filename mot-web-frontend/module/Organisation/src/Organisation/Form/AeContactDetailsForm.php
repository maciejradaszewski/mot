<?php

namespace Organisation\Form;

use DvsaClient\ViewModel\AbstractFormModel;
use DvsaClient\ViewModel\ContactDetailFormModel;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Organisation\OrganisationContactDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use Zend\Stdlib\Parameters;

class AeContactDetailsForm extends AbstractFormModel
{
    const FIELD_IS_CORR_ADDR_THE_SAME = 'isCorrContactDetailsSame';

    private $isCorrAddressTheSame = true;
    /**
     * @var OrganisationContactDto
     */
    private $busContact;
    /**
     * @var ContactDetailFormModel
     */
    private $corrContactModel;

    private $cancelUrl;

    public function __construct(OrganisationDto $org)
    {
        $this->corrContactModel = new ContactDetailFormModel(OrganisationContactTypeCode::CORRESPONDENCE);

        $this->fromDto($org);
    }

    public function fromPost(Parameters $data)
    {
        $this->isCorrAddressTheSame = ((bool) $data->get(self::FIELD_IS_CORR_ADDR_THE_SAME) === true);

        $this->getCorrContactModel()->fromPost($data);

        return $this;
    }

    public function fromDto(OrganisationDto $org)
    {
        //  logical block :: get contact details from dto, if empty init them
        $this->busContact = $org->getRegisteredCompanyContactDetail() ?: new OrganisationContactDto();
        $corrContact = $org->getCorrespondenceContactDetail() ?: new OrganisationContactDto();

        //  logical block :: fill from dto
        $this->getCorrContactModel()->fromDto($corrContact);

        //  logical block :: check is corr address same as bus address or empty, and fill with bus address if same
        $corrContactAddress = $corrContact->getAddress();
        if (
            AddressDto::isEquals($this->busContact->getAddress(), $corrContactAddress)
            || !$corrContactAddress instanceof AddressDto
            || $corrContactAddress->isEmpty()
        ) {
            $this->isCorrAddressTheSame = true;

            $this->getCorrContactModel()->getAddressModel()->fromDto($this->busContact->getAddress());
        } else {
            $this->isCorrAddressTheSame = false;
        }

        return $this;
    }

    public function toDto()
    {
        //  logical block :: fill contacts
        $corrContact = $this->getCorrContactModel()->toDto(new OrganisationContactDto());

        if ($this->isCorrAddressTheSame() === true) {
            $corrContact->setAddress($this->busContact->getAddress());
        }

        //  logical block :: assemble dto
        $aeDto = new OrganisationDto();
        $aeDto
            ->setContacts([$corrContact]);

        return $aeDto;
    }

    public function isValid()
    {
        $this->resetErrors();

        $corrModel = $this->getCorrContactModel();

        $isEmailValid = $corrModel->getEmailModel()->isValid(OrganisationContactTypeCode::CORRESPONDENCE);
        $isPhoneValid = $corrModel->getPhoneModel()->isValid(OrganisationContactTypeCode::CORRESPONDENCE);

        $isAddressValid = true;
        if ($this->isCorrAddressTheSame() === false) {
            $isAddressValid = $corrModel->getAddressModel()->isValid(OrganisationContactTypeCode::CORRESPONDENCE);
        }

        return $isEmailValid && $isPhoneValid && $isAddressValid;
    }

    /**
     * @return boolean
     */
    public function isCorrAddressTheSame()
    {
        return (bool) $this->isCorrAddressTheSame;
    }

    /**
     * @return OrganisationContactDto
     */
    public function getBusContact()
    {
        return $this->busContact;
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
     * @return $this
     */
    public function setCancelUrl($url)
    {
        $this->cancelUrl = $url;
        return $this;
    }
}
