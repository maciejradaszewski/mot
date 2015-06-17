<?php

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class RegisterUser
{
    private $result;
    private $input = [];

    public function execute()
    {
        $urlBuilder = UrlBuilder::userAccount();

        $this->result = TestShared::execCurlFormPostForJsonFromUrlBuilder(null, $urlBuilder, $this->input);
    }

    public function reset()
    {
        $this->input = [];
        $this->result = null;
    }

    public function errorMessage()
    {
        return TestShared::errorMessages($this->result);
    }

    private function setInputValue($name, $value)
    {
        if (!empty($value)) {
            $this->input[$name] = $value;
        }
    }

    public function setTitle($value)
    {
        $this->setInputValue('title', $value);
    }

    public function setFirstName($value)
    {
        $this->setInputValue('firstName', $value);
    }

    public function setSurname($value)
    {
        $this->setInputValue('surname', $value);
    }

    public function setDateOfBirth($value)
    {
        $this->setInputValue('dateOfBirth', $value);
    }

    public function setGender($value)
    {
        $this->setInputValue('gender', $value);
    }

    public function setAddressLine1($value)
    {
        $this->setInputValue('addressLine1', $value);
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

    public function setPassword($value)
    {
        $this->setInputValue('password', $value);
    }

    public function setPasswordConfirmation($value)
    {
        $this->setInputValue('passwordConfirmation', $value);
    }

    public function setDrivingLicenceNumber($value)
    {
        $this->setInputValue('drivingLicenceNumber', $value);
    }

    public function setDrivingLicenceRegion($value)
    {
        $this->setInputValue('drivingLicenceRegion', $value);
    }
}
