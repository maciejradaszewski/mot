<?php

namespace Organisation\Form;

use DvsaClient\ViewModel\AbstractFormModel;
use DvsaCommon\Dto\Contact\AddressDto;
use DvsaCommon\Dto\Contact\EmailDto;
use DvsaCommon\Dto\Contact\PhoneDto;
use DvsaCommon\Dto\Organisation\OrganisationContactDto;
use DvsaCommon\Dto\Organisation\OrganisationDto;
use DvsaCommon\Enum\OrganisationContactTypeCode;
use DvsaCommon\Enum\PhoneContactTypeCode;
use DvsaCommon\Utility\ArrayUtils;
use Zend\Stdlib\Hydrator\Reflection;

/**
 * Representation of AE creation form.
 */
class AeCreateForm extends AbstractFormModel
{
    private $organisationName;
    private $tradingAs;
    private $organisationType;
    private $companyType;
    private $registeredCompanyNumber;

    private $addressLine1;
    private $addressLine2;
    private $addressLine3;
    private $town;
    private $postcode;
    private $country;

    private $email;
    private $emailConfirmation;
    private $phoneNumber;
    private $faxNumber;

    private $correspondenceContactDetailsSame;

    private $correspondenceAddressLine1;
    private $correspondenceAddressLine2;
    private $correspondenceAddressLine3;
    private $correspondenceTown;
    private $correspondencePostcode;
    private $correspondenceCountry;

    private $correspondenceEmail;
    private $correspondenceEmailConfirmation;
    private $correspondencePhoneNumber;
    private $correspondenceFaxNumber;
    private $correspondenceEmailSupply;

    /** @var AddressDto */
    private $busContact;

    public function __construct(OrganisationDto $org = null)
    {
        if ($org) {
            $this->organisationName = $org->getName();
            $this->tradingAs = $org->getTradingAs();
            $this->organisationType = $org->getOrganisationType();
            $this->companyType = $org->getCompanyType();
            $this->registeredCompanyNumber = $org->getRegisteredCompanyNumber();

            $this->busContact = $org->getContactByType(OrganisationContactTypeCode::REGISTERED_COMPANY);
            $corrContact = $org->getContactByType(OrganisationContactTypeCode::CORRESPONDENCE);

            if (AddressDto::isEquals($this->busContact->getAddress(), $corrContact->getAddress())
                || (
                    $corrContact->getAddress() instanceof AddressDto
                    && $corrContact->getAddress()->isEmpty()
                )
            ) {
                $this->correspondenceContactDetailsSame = true;

                $corrContact->setAddress($this->busContact->getAddress());
            }

            $hydrator = new Reflection();
            $hydrator->hydrate($this->getContactDetail($this->busContact), $this);
            $hydrator->hydrate($this->getContactDetail($corrContact, 'correspondence'), $this);
        } else {
            $this->correspondenceContactDetailsSame = false;
        }
    }

    public function populate($data)
    {
        $hydrator = new Reflection();
        $hydrator->hydrate($data, $this);
    }

    public function populateToDto($postData)
    {
        $this->populate($postData);

        //  --  contacts    --
        $corrContact = $this->populateContactToDto($postData, 'correspondence');
        $contacts[] = $corrContact->setType(OrganisationContactTypeCode::CORRESPONDENCE);

        if ($this->isCorrespondenceContactDetailsSame()) {
            $corrContact->setAddress($this->busContact->getAddress());
        }

        //  --  assemble dto    --
        $aeDto = new OrganisationDto();
        $aeDto
            ->setName($this->getOrganisationName())
            ->setRegisteredCompanyNumber($this->getRegisteredCompanyNumber())
            ->setOrganisationType($this->getOrganisationType())
            ->setCompanyType($this->getCompanyType())
            ->setTradingAs($this->getTradingAs())
            ->setContacts($contacts);

        return $aeDto;
    }

    private function populateContactToDto($postData, $prefix = null)
    {
        if (!empty($prefix)) {
            $postData = ArrayUtils::removePrefixFromKeys($postData, $prefix);
        }

        //  --  set phones --
        $phones = [
            (new PhoneDto())
                ->setContactType(PhoneContactTypeCode::BUSINESS)
                ->setNumber(trim(ArrayUtils::tryGet($postData, 'phoneNumber')))
                ->setIsPrimary(true),

            (new PhoneDto())
                ->setContactType(PhoneContactTypeCode::FAX)
                ->setNumber(trim(ArrayUtils::tryGet($postData, 'faxNumber')))
                ->setIsPrimary(true),
        ];

        //  --  set email   --
        $emails = [
            (new EmailDto())
                ->setEmail(
                    $this->isCorrEmailSupply() && $prefix === 'correspondence'
                    ? trim(ArrayUtils::tryGet($postData, 'email'))
                    : null
                )
                ->setIsPrimary(true),
        ];

        $contact = new OrganisationContactDto();
        $contact
            ->setAddress((new AddressDto())->fromArray($postData))
            ->setPhones($phones)
            ->setEmails($emails);

        return $contact;
    }

    public function toArray()
    {
        $hydrator = new Reflection(false);

        $result = $hydrator->extract($this);

        //  --  remove fields are not used in form  --
        unset($result['busContact']);

        return $result;
    }

    public function getOrganisationName()
    {
        return $this->organisationName;
    }

    public function getOrganisationType()
    {
        return $this->organisationType;
    }

    public function getCompanyType()
    {
        return $this->companyType;
    }

    public function getRegisteredCompanyNumber()
    {
        return $this->registeredCompanyNumber;
    }

    public function isCorrespondenceContactDetailsSame()
    {
        return !empty($this->correspondenceContactDetailsSame);
    }

    public function getTradingAs()
    {
        return $this->tradingAs;
    }

    /**
     * @param OrganisationContactDto $contact
     * @param string                 $prefix
     *
     * @return array
     */
    private function getContactDetail($contact, $prefix = '')
    {
        $result = [];

        if ($contact) {
            $address = $contact->getAddress();
            if ($address instanceof AddressDto) {
                $result = $address->toArray();
            }

            $email = $contact->getPrimaryEmail();
            if ($email instanceof EmailDto) {
                $result['email'] = $email->getEmail();
                $result['emailConfirmation'] = $email->getEmail();
            }

            $phone = $contact->getPrimaryPhone();
            if ($phone instanceof PhoneDto) {
                $result['phoneNumber'] = $phone->getNumber();
            }

            /** @var PhoneDto $fax */
            $fax = $contact->getPrimaryFax();
            if ($fax instanceof PhoneDto) {
                $result['faxNumber'] = $fax->getNumber();
            }
        }

        if ($prefix) {
            $result = ArrayUtils::addPrefixToKeys($result, $prefix);
        }

        return $result;
    }

    /**
     * @return string
     */
    public function getCorrespondenceEmail()
    {
        return $this->correspondenceEmail;
    }

    /**
     * @return mixed
     */
    public function getCorrespondenceEmailConfirmation()
    {
        return $this->correspondenceEmailConfirmation;
    }

    /**
     * @return mixed
     */
    public function getCorrespondencePhoneNumber()
    {
        return $this->correspondencePhoneNumber;
    }

    /**
     * @return bool
     */
    public function isCorrEmailSupply()
    {
        return ((bool)$this->correspondenceEmailSupply === false);
    }

    /**
     * @return bool
     */
    public function isBusinessAddress()
    {
        if (empty($this->addressLine1) && empty($this->addressLine2) && empty($this->addressLine3) &&
            empty($this->town) && empty($this->postcode) && empty($this->country)) {
            return false;
        }
        return true;
    }

    public function isValid()
    {
        $errors = [];

        if ($this->isCorrEmailSupply()) {
            $email = $this->getCorrespondenceEmail();

            $validator = new \Zend\Validator\EmailAddress();
            if ($validator->isValid($email) === false) {
                $errors[] = [
                    'field'          => 'correspondenceEmail',
                    'displayMessage' => 'The email you entered is not valid'
                ];
            }

            if ($email != $this->getCorrespondenceEmailConfirmation()) {
                $errors[] = [
                    'field'          => 'correspondenceEmailConfirmation',
                    'displayMessage' => 'The confirmation email you entered is different'
                ];
            }
        }

        if (trim($this->getCorrespondencePhoneNumber()) === '') {
            $errors[] = [
                'field'          => 'correspondencePhoneNumber',
                'displayMessage' => 'A telephone number must be entered'
            ];
        }

        $this->addErrors($errors);

        return empty($errors);
    }
}
