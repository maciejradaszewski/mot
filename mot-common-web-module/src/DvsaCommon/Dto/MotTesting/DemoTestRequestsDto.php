<?php

namespace DvsaCommon\Dto\MotTesting;

use DvsaCommon\Dto\AbstractDataTransferObject;
use DvsaCommon\Formatting\PersonFullNameFormatter;

/**
 * Class DemoTestRequestsDto
 */
class DemoTestRequestsDto extends AbstractDataTransferObject
{
    private $id;
    private $username;
    private $userTelephoneNumber;
    private $userEmail;
    private $userFirstName;
    private $userMiddleName;
    private $userFamilyName;
    private $certificateGroupCode;
    private $vtsNumber;
    private $vtsPostcode;
    private $certificateDateAdded;

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserTelephoneNumber()
    {
        return $this->userTelephoneNumber;
    }

    /**
     * @param string $userTelephoneNumber
     */
    public function setUserTelephoneNumber($userTelephoneNumber)
    {
        $this->userTelephoneNumber = $userTelephoneNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserEmail()
    {
        return $this->userEmail;
    }

    /**
     * @param string $userEmail
     */
    public function setUserEmail($userEmail)
    {
        $this->userEmail = $userEmail;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserFirstName()
    {
        return $this->userFirstName;
    }

    /**
     * @param string $userFirstName
     */
    public function setUserFirstName($userFirstName)
    {
        $this->userFirstName = $userFirstName;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserMiddleName()
    {
        return $this->userMiddleName;
    }

    /**
     * @param string $userMiddleName
     */
    public function setUserMiddleName($userMiddleName)
    {
        $this->userMiddleName = $userMiddleName;
        return $this;
    }

    /**
     * @return string
     */
    public function getUserFamilyName()
    {
        return $this->userFamilyName;
    }

    /**
     * @param string $userFamilyName
     */
    public function setUserFamilyName($userFamilyName)
    {
        $this->userFamilyName = $userFamilyName;
        return $this;
    }

    /**
     * @return string
     */
    public function getCertificateGroupCode()
    {
        return $this->certificateGroupCode;
    }

    /**
     * @param string $certificateGroupCode
     */
    public function setCertificateGroupCode($certificateGroupCode)
    {
        $this->certificateGroupCode = $certificateGroupCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getVtsNumber()
    {
        return $this->vtsNumber;
    }

    /**
     * @param string $vtsNumber
     */
    public function setVtsNumber($vtsNumber)
    {
        $this->vtsNumber = $vtsNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getVtsPostcode()
    {
        return $this->vtsPostcode;
    }

    /**
     * @param string $vtsPostcode
     */
    public function setVtsPostcode($vtsPostcode)
    {
        $this->vtsPostcode = $vtsPostcode;
        return $this;
    }

    /**
     * @return string
     */
    public function getCertificateDateAdded()
    {
        return $this->certificateDateAdded;
    }

    /**
     * @param string $certificateDateAdded
     */
    public function setCertificateDateAdded($certificateDateAdded)
    {
        $this->certificateDateAdded = $certificateDateAdded;
        return $this;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getDisplayName()
    {
        return (new PersonFullNameFormatter())
            ->format($this->getUserFirstName(), $this->getUserMiddleName(), $this->getUserFamilyName());
    }
}
