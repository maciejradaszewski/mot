<?php
namespace Site\Form;

use DvsaClient\Entity\ContactDetail;
use DvsaCommon\Enum\SiteContactTypeCode;
use DvsaCommon\Utility\ArrayUtils;
use DvsaCommon\Auth\Assertion\UpdateVtsAssertion;

class VehicleTestingStationForm
{
    private $name;

    private $addressLine1;
    private $addressLine2;
    private $addressLine3;
    private $town;
    private $postcode;
    private $email;
    private $emailConfirmation;
    private $phoneNumber;
    private $faxNumber;

    private $correspondenceAddressLine1;
    private $correspondenceAddressLine2;
    private $correspondenceAddressLine3;
    private $correspondenceTown;
    private $correspondencePostcode;
    private $correspondenceEmail;
    private $correspondenceEmailConfirmation;
    private $correspondencePhoneNumber;
    private $correspondenceFaxNumber;

    private $correspondenceContactSame;

    /**
     * @var UpdateVtsAssertion
     */
    private $updateVtsAssertion;

    /**
     * @var int
     */
    private $vtsId;

    /**
     * @param UpdateVtsAssertion $updateVtsAssertion
     */
    public function setUpdateVtsAssertion(UpdateVtsAssertion $updateVtsAssertion)
    {
        $this->updateVtsAssertion = $updateVtsAssertion;
    }

    /**
     * @param int $vtsId
     */
    public function setVtsId($vtsId)
    {
        $this->vtsId = $vtsId;
    }

    public function canEditVtsName()
    {
        return $this->updateVtsAssertion->canUpdateName($this->vtsId);
    }

    public function canEditBusinessContact()
    {
        return $this->updateVtsAssertion->canUpdateBusinessDetails($this->vtsId);
    }

    public function canEditCorrespondenceContact()
    {
        return $this->updateVtsAssertion->canUpdateCorrespondenceDetails($this->vtsId);
    }

    public function populateFromInput(array $input)
    {
        $this->name = $input['name'];

        if ($this->canEditBusinessContact()) {
            $this->addressLine1 = $input['addressLine1'];
            $this->addressLine2 = $input['addressLine2'];
            $this->addressLine3 = $input['addressLine3'];
            $this->town = $input['town'];
            $this->postcode = $input['postcode'];
            $this->email = $input['email'];
            $this->emailConfirmation = $input['emailConfirmation'];
            $this->phoneNumber = $input['phoneNumber'];
            $this->faxNumber = $input['faxNumber'];
        }

        if ($this->canEditCorrespondenceContact()) {
            $this->correspondenceContactSame = (bool)$input['correspondenceContactSame'];

            $this->correspondenceAddressLine1 = $input['correspondenceAddressLine1'];
            $this->correspondenceAddressLine2 = $input['correspondenceAddressLine2'];
            $this->correspondenceAddressLine3 = $input['correspondenceAddressLine3'];
            $this->correspondenceTown = $input['correspondenceTown'];
            $this->correspondencePostcode = $input['correspondencePostcode'];
            $this->correspondenceEmail = $input['correspondenceEmail'];
            $this->correspondenceEmailConfirmation = $input['correspondenceEmailConfirmation'];
            $this->correspondencePhoneNumber = $input['correspondencePhoneNumber'];
            $this->correspondenceFaxNumber = $input['correspondenceFaxNumber'];
        }
    }

    public function toArray()
    {
        $data = [];
        $data['name'] = $this->name;

        $data = array_merge($data, $this->getBusinessContactData());
        $data = array_merge($data, $this->getCorrespondenceContactData());

        return $data;
    }

    public function toApiData()
    {
        $data = [];

        $data['name'] = $this->name;

        if ($this->canEditBusinessContact()) {
            $businessContactData = $this->getBusinessContactData();

            $data = array_merge($data, $businessContactData);
        }

        if ($this->canEditCorrespondenceContact()) {
            $correspondenceContactData = $this->getCorrespondenceContactSame()
                ? ArrayUtils::addPrefixToKeys($this->getBusinessContactData(), 'correspondence')
                : $this->getCorrespondenceContactData();

            $data = array_merge($data, $correspondenceContactData);
        }

        return $data;
    }

    private function getBusinessContactData()
    {
        $data = [];

        $data['addressLine1'] = $this->addressLine1;
        $data['addressLine2'] = $this->addressLine2;
        $data['addressLine3'] = $this->addressLine3;
        $data['town'] = $this->town;
        $data['postcode'] = $this->postcode;
        $data['email'] = $this->email;
        $data['emailConfirmation'] = $this->emailConfirmation;
        $data['phoneNumber'] = $this->phoneNumber;
        $data['faxNumber'] = $this->faxNumber;

        return $data;
    }

    private function getCorrespondenceContactData()
    {
        $data = [];

        $data['correspondenceAddressLine1'] = $this->correspondenceAddressLine1;
        $data['correspondenceAddressLine2'] = $this->correspondenceAddressLine2;
        $data['correspondenceAddressLine3'] = $this->correspondenceAddressLine3;
        $data['correspondenceTown'] = $this->correspondenceTown;
        $data['correspondencePostcode'] = $this->correspondencePostcode;
        $data['correspondenceEmail'] = $this->correspondenceEmail;
        $data['correspondenceEmailConfirmation'] = $this->correspondenceEmailConfirmation;
        $data['correspondencePhoneNumber'] = $this->correspondencePhoneNumber;
        $data['correspondenceFaxNumber'] = $this->correspondenceFaxNumber;

        return $data;
    }

    public function isValid()
    {
        return true;
    }

    /**
     * API data structure is nested, while the form uses flat structure of fields.
     */
    public function populateFromApi($apiData)
    {
        $this->name = $apiData['name'];

        /** @var ContactDetail[] $contacts */
        $contacts = $apiData['contacts'];

        /** @var ContactDetail $businessContact */
        $businessContact = ArrayUtils::firstOrNull(
            $contacts, function (ContactDetail $siteContact) {
                return $siteContact->getType() == SiteContactTypeCode::BUSINESS;
            }
        );

        /** @var ContactDetail $correspondenceContact */
        $correspondenceContact = ArrayUtils::firstOrNull(
            $contacts, function (ContactDetail $siteContact) {
                return $siteContact->getType() == SiteContactTypeCode::CORRESPONDENCE;
            }
        );

        if ($businessContact) {
            $this->addressLine1 = $businessContact->getAddress()->getAddressLine1();
            $this->addressLine2 = $businessContact->getAddress()->getAddressLine2();
            $this->addressLine3 = $businessContact->getAddress()->getAddressLine3();
            $this->town = $businessContact->getAddress()->getTown();
            $this->postcode = $businessContact->getAddress()->getPostcode();
            $this->email = $businessContact->getPrimaryEmailAddress();
            $this->emailConfirmation = $this->email;
            $this->phoneNumber = $businessContact->getPrimaryPhoneNumber();
            $this->faxNumber = $businessContact->getPrimaryFaxNumber();
        }

        if ($correspondenceContact) {
            $this->correspondenceAddressLine1 = $correspondenceContact->getAddress()->getAddressLine1();
            $this->correspondenceAddressLine2 = $correspondenceContact->getAddress()->getAddressLine2();
            $this->correspondenceAddressLine3 = $correspondenceContact->getAddress()->getAddressLine3();
            $this->correspondenceTown = $correspondenceContact->getAddress()->getTown();
            $this->correspondencePostcode = $correspondenceContact->getAddress()->getPostcode();
            $this->correspondenceEmail = $correspondenceContact->getPrimaryEmailAddress();
            $this->correspondenceEmailConfirmation = $this->correspondenceEmail;
            $this->correspondencePhoneNumber = $correspondenceContact->getPrimaryPhoneNumber();
            $this->correspondenceFaxNumber = $correspondenceContact->getPrimaryFaxNumber();
        }

        $this->correspondenceContactSame = null != $correspondenceContact && $this->areContactsSame();

        if ($this->correspondenceContactSame) {
            $this->clearCorrespondenceInputs();
        }
    }

    private function areContactsSame()
    {
        return $this->addressLine1 == $this->correspondenceAddressLine1
        && $this->addressLine2 == $this->correspondenceAddressLine2
        && $this->addressLine3 == $this->correspondenceAddressLine3
        && $this->town == $this->correspondenceTown
        && $this->postcode == $this->correspondencePostcode
        && $this->email == $this->correspondenceEmail
        && $this->phoneNumber == $this->correspondencePhoneNumber
        && $this->faxNumber == $this->correspondenceFaxNumber;
    }

    private function clearCorrespondenceInputs()
    {
        $this->correspondenceAddressLine1 = '';
        $this->correspondenceAddressLine2 = '';
        $this->correspondenceAddressLine3 = '';
        $this->correspondenceTown = '';
        $this->correspondenceEmail = '';
        $this->correspondenceEmailConfirmation = '';
        $this->correspondenceTown = '';
        $this->correspondencePhoneNumber = '';
        $this->correspondenceFaxNumber = '';
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCorrespondenceContactSame()
    {
        return $this->correspondenceContactSame;
    }

    public function getAddressLine1()
    {
        return $this->addressLine1;
    }

    public function getAddressLine2()
    {
        return $this->addressLine2;
    }

    public function getAddressLine3()
    {
        return $this->addressLine3;
    }

    public function getCorrespondenceAddressLine1()
    {
        return $this->correspondenceAddressLine1;
    }

    public function getCorrespondenceAddressLine2()
    {
        return $this->correspondenceAddressLine2;
    }

    public function getCorrespondenceAddressLine3()
    {
        return $this->correspondenceAddressLine3;
    }

    public function getCorrespondenceEmail()
    {
        return $this->correspondenceEmail;
    }

    public function getCorrespondenceFaxNumber()
    {
        return $this->correspondenceFaxNumber;
    }

    public function getCorrespondencePhoneNumber()
    {
        return $this->correspondencePhoneNumber;
    }

    public function getCorrespondencePostcode()
    {
        return $this->correspondencePostcode;
    }

    public function getCorrespondenceTown()
    {
        return $this->correspondenceTown;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getFaxNumber()
    {
        return $this->faxNumber;
    }

    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function getTown()
    {
        return $this->town;
    }
}
