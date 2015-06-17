<?php

require_once 'configure_autoload.php';

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

/**
 * Checks response after posting to api to create a new site
 */
class CreateSite
{
    private $result;
    private $input = [];
    public $username = 'areaoffice1user';
    public $password = TestShared::PASSWORD;

    public function execute()
    {
        $urlBuilder = (new UrlBuilder())->vehicleTestingStation();
        $this->setInputValue('nonWorkingDayCountry', 'GBENG');
        $this->result = TestShared::execCurlFormPostForJsonFromUrlBuilder($this, $urlBuilder, $this->input);
    }

    public function reset()
    {
        $this->input = [];
        $this->result = null;
    }

    public function result()
    {
        return $this->result['data'];
    }

    private function setInputValue($name, $value)
    {
        if (!empty($value)) {
            $this->input[$name] = $value;
        }
    }

    public function setName($value)
    {
        $this->setInputValue('name', $value);
    }

    public function setAddressLine1($value)
    {
        $this->setInputValue('addressLine1', $value);
    }

    public function setAddressLine2($value)
    {
        $this->setInputValue('addressLine2', $value);
    }

    public function setAddressLine3($value)
    {
        $this->setInputValue('addressLine3', $value);
    }

    public function setTown($value)
    {
        $this->setInputValue('town', $value);
    }

    public function setPostcode($value)
    {
        $this->setInputValue('postcode', $value);
    }

    public function setPhoneNumber($value)
    {
        $this->setInputValue('phoneNumber', $value);
    }

    public function setEmail($value)
    {
        $this->setInputValue('email', $value);
    }

    public function setEmailConfirmation($value)
    {
        $this->setInputValue('emailConfirmation', $value);
    }

    public function setCorrespondenceAddressSameYes($value)
    {
        $this->setInputValue('correspondenceContactDetails', $value);
    }

    public function setCorrespondenceAddressLine1($value)
    {
        $this->setInputValue('correspondenceAddressLine1', $value);
    }

    public function setCorrespondenceAddressLine2($value)
    {
        $this->setInputValue('correspondenceAddressLine2', $value);
    }

    public function setCorrespondenceAddressLine3($value)
    {
        $this->setInputValue('correspondenceAddressLine3', $value);
    }

    public function setCorrespondenceTown($value)
    {
        $this->setInputValue('correspondenceTown', $value);
    }

    public function setCorrespondencePostcode($value)
    {
        $this->setInputValue('correspondencePostcode', $value);
    }

    public function setCorrespondenceEmail($value)
    {
        $this->setInputValue('correspondenceEmail', $value);
    }

    public function setCorrespondenceEmailConfirmation($value)
    {
        $this->setInputValue('correspondenceEmailConfirmation', $value);
    }

    public function setCorrespondencePhoneNumber($value)
    {
        $this->setInputValue('correspondencePhoneNumber', $value);
    }

    public function errorMessage()
    {
        return TestShared::errorMessages($this->result);
    }
}
