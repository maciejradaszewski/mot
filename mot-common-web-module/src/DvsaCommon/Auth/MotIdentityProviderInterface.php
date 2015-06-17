<?php

namespace DvsaCommon\Auth;

/**
 * Provides the current user's identity to objects that require it.
 */
interface MotIdentityProviderInterface
{
    /** @return MotIdentityInterface */
    public function getIdentity();
}
