<?php

namespace DvsaCommon\Auth;

/**
 * Interface for the logged-in user's identity. Does *not* cover roles, etc.
 */
interface MotIdentityInterface
{
    /**
     * Returns the username e.g. user1@example.com
     */
    public function getUsername();

    /**
     * Returns the user ID e.g. 5001
     */
    public function getUserId();

    /**
     * @return string OpenAm unique identifier (UUID)
     */
    public function getUuid();

    /**
     * @return boolean
     */
    public function isPasswordChangeRequired();

    /**
     * @return boolean
     */
    public function isAccountClaimRequired();
}
