<?php

namespace DvsaCommon\Dto\Organisation;

use DvsaCommon\Dto\Common\AuthForAeStatusDto;
use DvsaCommon\Dto\Contact\AddressDto;

/**
 * Class AuthorisedExaminerListItemDto
 *
 * @package DvsaCommon\Dto\Organisation
 */
class AuthorisedExaminerListItemDto extends AuthorisedExaminerAuthorisationDto
{


    private $id;
    private $type;
    private $status;
    private $name;
    /** @var AddressDto */
    private $address;
    private $phone;

    /**
     * @param AddressDto $address
     *
     * @return AuthorisedExaminerListItemDto
     */
    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return AddressDto
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $phone
     *
     * @return AuthorisedExaminerListItemDto
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }


    /**
     * @param mixed $id
     *
     * @return AuthorisedExaminerListItemDto
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $name
     *
     * @return AuthorisedExaminerListItemDto
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param AuthForAeStatusDto $status
     *
     * @return AuthorisedExaminerListItemDto
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return AuthForAeStatusDto
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $type
     *
     * @return AuthorisedExaminerListItemDto
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
