<?php
namespace Site\Form;

use DvsaClient\ViewModel\AbstractFormModel;
use DvsaClient\ViewModel\EmailFormModel;
use DvsaCommon\Dto\Contact\EmailDto;
use DvsaCommon\Dto\Contact\PhoneDto;
use DvsaCommon\Dto\Site\SiteContactDto;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommon\Enum\PhoneContactTypeCode;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommon\Utility\ArrayUtils;

class VtsContactDetailsUpdateForm extends AbstractFormModel
{
    /** @var  VehicleTestingStationDto */
    private $vtsDto;
    /** @var  SiteContactDto */
    private $busContact;
    /** @var  EmailFormModel */
    private $busEmail;
    /** @var  PhoneDto */
    private $busPhone;

    public function __construct()
    {
        $this->busEmail = new EmailFormModel(SiteContactTypeCode::BUSINESS);

        $this->busPhone = new PhoneDto();
    }

    public function populateFromPost(array $input)
    {
        $this->busEmail->fromPost($input);
        // there is negative question "I don't want to supply an email address", therefore
        // there need to set opposite value (if get true, set false, and in other way)
        $this->busEmail->setIsSupply(
            (bool)ArrayUtils::tryGet($input, $this->busEmail->getFieldName(EmailFormModel::$FIELD_IS_SUPPLY))
            === false
        );

        $this->busPhone->setNumber(ArrayUtils::tryGet($input, SiteContactTypeCode::BUSINESS . 'PhoneNumber'));
    }

    public function toApiData()
    {
        $dto = $this->busContact;
        $dto->setAddress(null);

        //  --  set email   --
        //  if user don't want provide email, but email was already provided, there send empty object
        $emailDto = ($this->busEmail->isSupply() ? $this->busEmail->getDto() : new EmailDto());
        $emailDto->setIsPrimary(true);

        $dto->setEmails([$emailDto]);

        //  --  set phone   --
        $this->busPhone
            ->setIsPrimary(true)
            ->setContactType(PhoneContactTypeCode::BUSINESS);

        $dto->setPhones([$this->busPhone]);

        return $dto;
    }

    /**
     * API data structure is nested, while the form uses flat structure of fields.
     */
    public function populateFromApi(VehicleTestingStationDto $vtsDto)
    {
        $this->vtsDto = $vtsDto;

        /** @var SiteContactDto $busContact */
        $this->busContact = $this->vtsDto->getContactByType(SiteContactTypeCode::BUSINESS);

        if ($this->busContact instanceof SiteContactDto) {
            $this->busEmail->fromDto($this->busContact->getPrimaryEmail());

            $this->busPhone = $this->busContact->getPrimaryPhone() ?: $this->busPhone;
        }
    }

    public function getDto()
    {
        return $this->vtsDto;
    }

    public function getBusEmail()
    {
        return $this->busEmail;
    }

    public function getBusPhone()
    {
        return $this->busPhone;
    }

    public function isValid()
    {
        $errors = [];

        if ($this->busEmail->isSupply()) {
            $email = $this->busEmail->getEmail();

            $validator = new \Zend\Validator\EmailAddress();
            if ($validator->isValid($email) === false) {
                $errors[] = [
                    'field'          => $this->busEmail->getFieldName(EmailFormModel::$FIELD_EMAIL),
                    'displayMessage' => 'The email you entered is not valid'
                ];
            }

            if ($email != $this->busEmail->getEmailConfirm()) {
                $errors[] = [
                    'field'          => $this->busEmail->getFieldName(EmailFormModel::$FIELD_EMAIL_CONFIRM),
                    'displayMessage' => 'The confirmation email you entered is different'
                ];
            }
        }

        if (trim($this->busPhone->getNumber()) === '') {
            $errors[] = [
                'field'          => SiteContactTypeCode::BUSINESS . 'PhoneNumber',
                'displayMessage' => 'A telephone number must be entered'
            ];
        }

        $this->addErrors($errors);

        return empty($errors);
    }
}
