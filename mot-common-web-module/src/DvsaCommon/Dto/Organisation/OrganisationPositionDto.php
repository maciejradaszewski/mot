<?php

namespace DvsaCommon\Dto\Organisation;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\Person\PersonDto;
use DvsaCommon\Enum\BusinessRoleStatusCode;

/**
 * Class OrganisationPositionDto
 *
 * @package DvsaCommon\Dto\Organisation
 */
class OrganisationPositionDto extends AbstractDataTransferObject
{
    private $person;

    /**
     * @var string $role
     */
    private $role;

    /** @var int $id */
    private $id;

    /** @var  int $status */
    private $status;

    /** @var  string $actionedOn */
    private $actionedOn;

    /**
     * @param string $role
     * @see OrganisationBusinessRoleCode
     *
     * @return OrganisationPositionDto
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return string
     * @see OrganisationBusinessRoleCode
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param PersonDto $person
     *
     * @return OrganisationPositionDto
     */
    public function setPerson($person)
    {
        $this->person = $person;
        return $this;
    }

    /**
     * @return PersonDto
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @param int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $actionedOn
     *
     * @return $this
     */
    public function setActionedOn($actionedOn)
    {
        $this->actionedOn = $actionedOn;
        return $this;
    }

    /**
     * @return string
     */
    public function getActionedOn()
    {
        return $this->actionedOn;
    }

    public function isPending()
    {
        return $this->status == BusinessRoleStatusCode::PENDING;
    }

    public function isActive()
    {
        return $this->status == BusinessRoleStatusCode::ACTIVE;
    }
}
