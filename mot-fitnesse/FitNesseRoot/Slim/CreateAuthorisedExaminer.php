<?php

use MotFitnesse\Util\AuthorisedExaminerUrlBuilder;
use MotFitnesse\Util\TestShared;

/**
 * Class CreateAuthorisedExaminer
 */
class CreateAuthorisedExaminer
{
    public $username = 'areaoffice1user';
    public $password = TestShared::PASSWORD;

    private $result;
    private $input = [];

    public function execute()
    {
        $urlBuilder = AuthorisedExaminerUrlBuilder::authorisedExaminer();

        $this->result = TestShared::execCurlFormPostForJsonFromUrlBuilder($this, $urlBuilder, $this->input);
    }

    public function reset()
    {
        $this->input = [];
        $this->result = null;
    }

    public function result()
    {
        debug($this->result);
        return $this->result['data'];
    }

    public function getId()
    {
        return $this->result()['id'];
    }

    private function setInputValue($name, $value)
    {
        if (!empty($value)) {
            $this->input[$name] = $value;
        }
    }

    public function setOrganisationName($value)
    {
        $this->setInputValue('organisationName', $value);
    }

    public function setTradingAs($value)
    {
        $this->setInputValue('tradingAs', $value);
    }


    public function setAuthorisedExaminerReference($value)
    {
        $this->setInputValue('authorisedExaminerReference', $value);
    }

    public function setOrganisationType($value)
    {
        $this->setInputValue('organisationType', $value);
    }

    public function setRegisteredCompanyNumber($value)
    {
        $this->setInputValue('registeredCompanyNumber', $value);
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
        if ('Yes' === $value) {
            $this->setInputValue('correspondenceContactDetailsSame', false);
        } else {
            $this->setInputValue('correspondenceContactDetailsSame', true);
        }
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
}
