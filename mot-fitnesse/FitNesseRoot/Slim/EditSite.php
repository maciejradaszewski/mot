<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;
use MotFitnesse\Util\CredentialsProvider;

/**
 * Checks response after put request to api to update site name
 */
class EditSite
{
    private $result;
    private $siteId;
    private $user;
    private $credential;
    private $defaultSiteData = [
        "name" => "VTS", "addressLine1" => "Vts Road", "addressLine2" => "3rd Floor", "addressLine3" => "Behind Corner",
        "town" => "Bristol", "postcode" => "22-100", "email" => "vts@bristol.com", "emailConfirmation" => "vts@bristol.com",
        "phoneNumber" => "09123456", "correspondenceAddressLine1" => "Vts Flat 2", "correspondenceAddressLine2" => "4th Floor",
        "correspondenceAddressLine3" => "Infront of a Corner", "correspondenceTown" => "Bristol", "correspondencePostcode" => "11-200",
        "correspondenceEmail" => "bristol@vts.com", "correspondenceEmailConfirmation" => "bristol@vts.com",
        "correspondencePhoneNumber" => "222333444"
    ];

    private $editSiteData = [
        "name" => "YarcoWheels", "addressLine1" => "Funny Road", "addressLine2" => "4th Floor", "addressLine3" => "Infront of a Corner",
        "town" => "Mordor", "postcode" => "11-200", "email" => "www@www.com", "emailConfirmation" => "www@www.com",
        "phoneNumber" => "09654321", "correspondenceAddressLine1" => "Vts Flat 5", "correspondenceAddressLine2" => "5th Floor",
        "correspondenceAddressLine3" => "Behind Corner", "correspondenceTown" => "London", "correspondencePostcode" => "22-100",
        "correspondenceEmail" => "london@vts.com", "correspondenceEmailConfirmation" => "london@vts.com",
        "correspondencePhoneNumber" => "555666777"
    ];

    private function updateSiteWithDefaultData()
    {
        $urlBuilder = $this->getUrlBuilder();
        TestShared::execCurlFormPutForJsonFromUrlBuilder($this->getCredential(), $urlBuilder, $this->defaultSiteData);
    }


    private function prepare()
    {
        $this->updateSiteWithDefaultData();
    }

    private function getUrlBuilder()
    {
        return (new UrlBuilder())->vehicleTestingStation()->routeParam('id', $this->siteId);
    }

    public function execute()
    {
        $this->prepare();
        $urlBuilder = $this->getUrlBuilder();
        $credentials = new CredentialsProvider($this->user, TestShared::PASSWORD);

        TestShared::execCurlFormPutForJsonFromUrlBuilder($credentials , $urlBuilder, $this->editSiteData);
        $this->result = TestShared::execCurlForJsonFromUrlBuilder($this->getCredential(), $urlBuilder);
    }

    public function reset()
    {
        $this->result = null;
    }

    public function result()
    {
        return $this->result['data'];
    }

    public function setId($value)
    {
        $this->siteId = $value;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    public function name()
    {
        return $this->isUpdated($this->result()['name'], 'name');
    }

    public function addressLine1()
    {
        $contact = $this->getBusinessContact();
        $value = $contact['address']['addressLine1'];
        return $this->isUpdated($value, 'addressLine1');
    }

    public function addressLine2()
    {
        $contact = $this->getBusinessContact();
        $value = $contact['address']['addressLine2'];
        return $this->isUpdated($value, 'addressLine2');
    }

    public function addressLine3()
    {
        $contact = $this->getBusinessContact();
        $value = $contact['address']['addressLine3'];
        return $this->isUpdated($value, 'addressLine3');
    }

    public function town()
    {
        $contact = $this->getBusinessContact();
        $value = $contact['address']['town'];
        return $this->isUpdated($value, 'town');
    }

    public function postcode()
    {
        $contact = $this->getBusinessContact();
        $value = $contact['address']['postcode'];
        return $this->isUpdated($value, 'postcode');
    }

    public function email()
    {
        $contact = $this->getBusinessContact();
        $emails = $contact['emails'];
        $email = array_shift($emails);

        $value = $email['email'];
        return $this->isUpdated($value, 'email');
    }

    public function phoneNumber()
    {
        $contact = $this->getBusinessContact();
        $phones = $contact['phones'];
        $phone = array_shift($phones);

        $value = $phone['number'];
        return $this->isUpdated($value, 'phoneNumber');
    }

    public function correspondenceAddressLine1()
    {
        $contact = $this->getCorrespondenceAContact();
        $value = $contact['address']['addressLine1'];
        return $this->isUpdated($value, 'correspondenceAddressLine1');
    }

    public function correspondenceAddressLine2()
    {
        $contact = $this->getCorrespondenceAContact();
        $value = $contact['address']['addressLine2'];
        return $this->isUpdated($value, 'correspondenceAddressLine2');
    }

    public function correspondenceAddressLine3()
    {
        $contact = $this->getCorrespondenceAContact();
        $value = $contact['address']['addressLine3'];
        return $this->isUpdated($value, 'correspondenceAddressLine3');
    }

    public function correspondenceTown()
    {
        $contact = $this->getCorrespondenceAContact();
        $value = $contact['address']['town'];
        return $this->isUpdated($value, 'correspondenceTown');
    }

    public function correspondencePostcode()
    {
        $contact = $this->getCorrespondenceAContact();
        $value = $contact['address']['postcode'];
        return $this->isUpdated($value, 'correspondencePostcode');
    }

    public function correspondenceEmail()
    {
        $contact = $this->getCorrespondenceAContact();
        $emails = $contact['emails'];
        $email = array_shift($emails);

        $value = $email['email'];
        return $this->isUpdated($value, 'correspondenceEmail');
    }

    public function correspondencePhoneNumber()
    {
        $contact = $this->getCorrespondenceAContact();
        $phones = $contact['phones'];
        $phone = array_shift($phones);

        $value = $phone['number'];
        return $this->isUpdated($value, 'correspondencePhoneNumber');
    }

    public function errorMessage()
    {
        return TestShared::errorMessages($this->result);
    }

    private function getCredential()
    {
        if (is_null($this->credential)) {
            $testSupportHelper = new TestSupportHelper();
            $ao1 = $testSupportHelper->createAreaOffice1User();

            $this->credential = new \MotFitnesse\Util\CredentialsProvider(
                $ao1['username'],
                $ao1['password']
            );
        }

        return $this->credential;
    }

    /**
     * @return array
     */
    private function getBusinessContact()
    {
        $contacts = $this->result()['contacts'];
        foreach ($contacts as $contact) {
            if ($contact['type'] === 'BUS') {
                return $contact;
            }
        }

        return [];
    }

    /**
     * @return array
     */
    private function getCorrespondenceAContact()
    {
        $contacts = $this->result()['contacts'];
        foreach ($contacts as $contact) {
            if ($contact['type'] === 'CORR') {
                return $contact;
            }
        }

        return [];
    }

    private function isUpdated($value, $fieldName)
    {
        if ($value === $this->editSiteData[$fieldName]) {
            return 'true';
        }

        return 'false';
    }
}
