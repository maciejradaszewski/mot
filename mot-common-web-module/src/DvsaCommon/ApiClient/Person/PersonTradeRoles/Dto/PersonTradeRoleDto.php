<?php

namespace DvsaCommon\ApiClient\Person\PersonTradeRoles\Dto;

use DvsaCommon\DtoSerialization\ReflectiveDtoInterface;

class PersonTradeRoleDto implements ReflectiveDtoInterface
{
    private $positionId;
    private $workplaceName;
    private $workplaceId;
    private $roleCode;
    private $address;
    private $aeId;
    private $number;

    public function getPositionId()
    {
        return $this->positionId;
    }

    public function setPositionId($positionId)
    {
        $this->positionId = $positionId;
        return $this;
    }

    public function getWorkplaceName()
    {
        return $this->workplaceName;
    }

    public function setWorkplaceName($workplaceName)
    {
        $this->workplaceName = $workplaceName;
        return $this;
    }

    public function getWorkplaceId()
    {
        return $this->workplaceId;
    }

    public function setWorkplaceId($workplaceId)
    {
        $this->workplaceId = $workplaceId;
        return $this;
    }

    public function getRoleCode()
    {
        return $this->roleCode;
    }

    public function setRoleCode($roleCode)
    {
        $this->roleCode = $roleCode;
        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @return string
     */
    public function getAeId()
    {
        return $this->aeId;
    }

    /**
     * @param string $aeId
     * @return PersonTradeRoleDto
     */
    public function setAeId($aeId)
    {
        $this->aeId = $aeId;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     * @return PersonTradeRoleDto
     */
    public function setNumber($number)
    {
        $this->number = $number;
        return $this;
    }
}
