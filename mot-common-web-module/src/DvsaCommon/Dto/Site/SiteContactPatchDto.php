<?php
namespace DvsaCommon\Dto\Site;


use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\Contact\AddressDto;

class SiteContactPatchDto extends AbstractDataTransferObject {
    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * @return AddressDto
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param AddressDto $address
     */
    public function setAddress($address)
    {
        $this->address = $address;
    }

    /** @var  string */
    private $email;
    /** @var  string */
    private $phone;
    /** @var AddressDto */
    private $address;
}