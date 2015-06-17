<?php

namespace DvsaCommon\Dto\Security;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\CommonTrait\CommonIdentityDtoTrait;
use DvsaCommon\Dto\Person\PersonDto;

class RolesMapDto extends AbstractDataTransferObject
{
    use CommonIdentityDtoTrait;

    /** @var  PersonDto */
    private $person;

    /** @var  RoleDto */
    private $role;
    /** @var  RoleStatusDto */
    private $roleStatus;

    /** @var  \DateTime */
    private $statusChangedOn;
    /** @var  \DateTime */
    private $validFrom;
    /** @var  \DateTime */
    private $expiryDate;


    /**
     * @return PersonDto
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param PersonDto $person
     *
     * @return $this
     */
    public function setPerson($person)
    {
        $this->person = $person;
        return $this;
    }


    /**
     * @return RoleDto
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param RoleDto $role
     *
     * @return $this
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }


    /**
     * @return RoleStatusDto
     */
    public function getRoleStatus()
    {
        return $this->roleStatus;
    }

    /**
     * @param RoleStatusDto $roleStatus
     *
     * @return $this
     */
    public function setRoleStatus($roleStatus)
    {
        $this->roleStatus = $roleStatus;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStatusChangedOn()
    {
        return $this->statusChangedOn;
    }

    /**
     * @return $this
     */
    public function setStatusChangedOn($statusChangedOn)
    {
        $this->statusChangedOn = $statusChangedOn;
        return $this;
    }

    public function getValidFrom()
    {
        return $this->validFrom;
    }

    /**
     * @return $this
     */
    public function setValidFrom($validFrom)
    {
        $this->validFrom = $validFrom;
        return $this;
    }

    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * @return $this
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;
        return $this;
    }
}
