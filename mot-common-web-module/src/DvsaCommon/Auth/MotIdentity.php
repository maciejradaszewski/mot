<?php

namespace DvsaCommon\Auth;

/**
 * Temporary class to represent the identity.
 *
 * Needs to supercede \DvsaAuthentication\Model\Identity
 */
class MotIdentity implements MotIdentityInterface
{

    private $username;

    private $userId;

    public function __construct($userId, $username)
    {
        $this->userId = $userId;
        $this->username = $username;
    }

    /**
     * Returns the username e.g. user1@example.com
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Returns the user ID e.g. 5001
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return mixed
     */
    public function getUuid()
    {
        return $this->username;
    }

    /**
     * stubbing the interface methods for account claim
     * @return bool|void
     */
    public function isAccountClaimRequired()
    {
    }

    /**
     * stubbing the interface methods for password change
     * @return bool|void
     */
    public function isPasswordChangeRequired()
    {
    }
}
