<?php

namespace DvsaCommon\Dto\Organisation;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Dto\AreaOffice\AreaOfficeDto;
use DvsaCommon\Dto\Common\AuthForAeStatusDto;

/**
 * Class AuthorisedExaminerAuthorisationDto
 *
 * @package DvsaCommon\Dto\Organisation
 */
class AuthorisedExaminerAuthorisationDto extends AbstractDataTransferObject
{
    private $authorisedExaminerRef;
    private $statusChangedOn;
    private $validFrom;
    private $expiryDate;

    /** @var AuthForAeStatusDto */
    private $status;

    /** @var  AreaOfficeDto */
    private $assignedAreaOffice;

//    /** @var  string */
//    private $assignedAreaOfficeLabel;


    /**
     * @param string $authorisedExaminerRef
     *
     * @return AuthorisedExaminerAuthorisationDto
     */
    public function setAuthorisedExaminerRef($authorisedExaminerRef)
    {
        $this->authorisedExaminerRef = $authorisedExaminerRef;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthorisedExaminerRef()
    {
        return $this->authorisedExaminerRef;
    }

    /**
     * @param AuthForAeStatusDto $status
     *
     * @return AuthorisedExaminerAuthorisationDto
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
     * @param \DateTime $value
     * @return AuthorisedExaminerAuthorisationDto
     */
    public function setStatusChangedOn($value)
    {
        $this->statusChangedOn = strtotime($value) > 0 ? $value : null;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getStatusChangedOn()
    {
        return $this->statusChangedOn;
    }

    /**
     * @param \DateTime $value
     *
     * @return AuthorisedExaminerAuthorisationDto
     */
    public function setValidFrom($value)
    {
        $this->validFrom = $value;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getValidFrom()
    {
        return $this->validFrom;
    }

    /**
     * @param \DateTime $value
     *
     * @return AuthorisedExaminerAuthorisationDto
     */
    public function setExpiryDate($value)
    {
        $this->expiryDate = $value;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * @return AreaOfficeDto
     */
    public function getAssignedAreaOffice()
    {
        return $this->assignedAreaOffice;
    }

    /**
     * @param AreaOfficeDto $assignedAreaOffice
     * @return $this
     */
    public function setAssignedAreaOffice($assignedAreaOffice)
    {
        $this->assignedAreaOffice = $assignedAreaOffice;

        return $this;
    }
}
