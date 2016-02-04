<?php

namespace DvsaCommonTest\TestUtils;

class UpdateAeContactTestArguments
{
    private $addressLine1;
    private $addressLine2;
    private $addressLine3;
    private $town;
    private $postcode;
    private $country;
    private $phone;
    private $email;
    private $organisationContactType;

    public function __construct($addressLine1, $addressLine2, $addressLine3, $town, $postcode, $country, $phone, $email, $organisationContactType)
    {
        $this->addressLine1 = $addressLine1;
        $this->addressLine2 = $addressLine2;
        $this->addressLine3 = $addressLine3;
        $this->town = $town;
        $this->postcode = $postcode;
        $this->country = $country;
        $this->phone = $phone;
        $this->email = $email;
        $this->organisationContactType = $organisationContactType;
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

    public function getTown()
    {
        return $this->town;
    }

    public function getPostcode()
    {
        return $this->postcode;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getOrganisationContactType()
    {
        return $this->organisationContactType;
    }
}
