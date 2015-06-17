<?php

use MotFitnesse\Util\TestShared;
use MotFitnesse\Util\UrlBuilder;

class CreateVehicle
{
    private $result;
    private $input = [];
    private $testerUsername;

    public function __construct($testerUsername)
    {
        $this->testerUsername = $testerUsername;
    }

    public function execute()
    {
        $urlBuilder = (new UrlBuilder)->vehicle();

        $this->setInputValue('makeOther', '');
        $this->setInputValue('modelOther', '');

        $this->result = TestShared::execCurlFormPostForJsonFromUrlBuilder(
            new \MotFitnesse\Util\CredentialsProvider($this->testerUsername,
                TestShared::PASSWORD),
            $urlBuilder,
            $this->input
        );
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
        if (isset($value)) {
            if (strtolower($value) == 'null') {
                $value = null;
            }
            $this->input[$name] = $value;
        }
    }

    public function setRegistrationNumber($value)
    {
        $this->setInputValue('registrationNumber', $value);
    }

    public function setEmptyVrmReason($value){
        $this->setInputValue('emptyVrmReason', $value);
    }

    public function setEmptyVinReason($value){
        $this->setInputValue('emptyVinReason', $value);
    }

    public function setVin($value)
    {
        $this->setInputValue('vin', $value);
    }

    public function setMake($value)
    {
        $this->setInputValue('make', $value);
    }

    public function setModel($value)
    {
        $this->setInputValue('model', $value);
    }

    public function setColour($value)
    {
        $this->setInputValue('colour', $value);
    }

    public function setSecondaryColour($value)
    {
        $this->setInputValue('secondaryColour', $value);
    }

    public function setDateOfFirstUse($value)
    {
        $this->setInputValue('dateOfFirstUse', $value);
    }

    public function setFuelType($value)
    {
        $this->setInputValue('fuelType', $value);
    }

    public function setTestClass($value)
    {
        $this->setInputValue('testClass', $value);
    }

    public function setCountryOfRegistration($value)
    {
        $this->setInputValue('countryOfRegistration', $value);
    }

    public function setCylinderCapacity($value)
    {
        $this->setInputValue('cylinderCapacity', $value);
    }

    public function setTransmissionType($value)
    {
        $this->setInputValue('transmissionType', $value);
    }

    public function setOtp($value)
    {
        $this->setInputValue('oneTimePassword', $value);
    }
}
