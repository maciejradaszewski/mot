<?php

namespace DvsaAuthentication;

use DvsaCommon\Auth\MotIdentityInterface;
use DvsaEntities\Entity\Person;

/**
 * Class Identity
 *
 * @package     DvsaAuthentication
 *
 * @description Wrapper for the identity used by the authentication system.
 */
class Identity implements MotIdentityInterface
{
    /**
     * @var \DvsaEntities\Entity\Person
     */
    protected $person;
    /**
     * @var string
     */
    protected $token;

    /** @var  string */
    protected $uuid;

    public function __construct(Person $person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * @return \DvsaEntities\Entity\Person
     * @codeCoverageIgnore
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * @return string
     * @codeCoverageIgnore
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     *
     * @return $this
     * @codeCoverageIgnore
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->person->getUsername();
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param $uuid
     *
     * @return $this
     */
    public function setUuid($uuid)
    {
        $this->uuid = $uuid;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->person->getId();
    }

    public function isAccountClaimRequired()
    {
        return $this->person->isAccountClaimRequired();
    }

    public function isPasswordChangeRequired()
    {
        return $this->person->isPasswordChangeRequired();
    }
}
