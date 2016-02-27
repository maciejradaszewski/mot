<?php

namespace DvsaCommon\Dto\Authn;

use DvsaCommon\DtoSerialization\ReflectiveDtoInterface;

class AuthenticatedUserDto implements ReflectiveDtoInterface
{
    /** @var int */
    private $userId;

    /** @var string */
    private $username;

    /** @var string */
    private $displayName;

    /** @var string */
    private $role;

    /** @var  bool */
    private $isAccountClaimRequired;

    /** @var  bool */
    private $isPasswordChangeRequired;

    /** @var  bool */
    private $isSecondFactorRequired;

    /** @var  \DateTime */
    private $passwordExpiryDate;

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return AuthenticatedUserDto
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * @return AuthenticatedUserDto
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * @param string $displayName
     * @return AuthenticatedUserDto
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
        return $this;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     * @return AuthenticatedUserDto
     */
    public function setRole($role)
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isIsAccountClaimRequired()
    {
        return $this->isAccountClaimRequired;
    }

    /**
     * @param boolean $isAccountClaimRequired
     * @return AuthenticatedUserDto
     */
    public function setIsAccountClaimRequired($isAccountClaimRequired)
    {
        $this->isAccountClaimRequired = $isAccountClaimRequired;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isIsPasswordChangeRequired()
    {
        return $this->isPasswordChangeRequired;
    }

    /**
     * @param boolean $isPasswordChangeRequired
     * @return AuthenticatedUserDto
     */
    public function setIsPasswordChangeRequired($isPasswordChangeRequired)
    {
        $this->isPasswordChangeRequired = $isPasswordChangeRequired;
        return $this;
    }

    /**
     * @return boolean
     */
    public function isIsSecondFactorRequired()
    {
        return $this->isSecondFactorRequired;
    }

    /**
     * @param boolean $isSecondFactorRequired
     * @return AuthenticatedUserDto
     */
    public function setIsSecondFactorRequired($isSecondFactorRequired)
    {
        $this->isSecondFactorRequired = $isSecondFactorRequired;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getPasswordExpiryDate()
    {
        return $this->passwordExpiryDate;
    }

    /**
     * @param \DateTime $passwordExpiryDate
     * @return AuthenticatedUserDto
     */
    public function setPasswordExpiryDate(\DateTime $passwordExpiryDate)
    {
        $this->passwordExpiryDate = $passwordExpiryDate;
        return $this;
    }
}