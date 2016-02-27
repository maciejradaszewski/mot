<?php

namespace DvsaCommon\Auth;

use Dvsa\Mot\Frontend\AuthenticationModule\Model\Identity;

/**
 * Provides the current user's identity to objects that require it.
 */
interface MotIdentityProviderInterface
{
    /** @return Identity */
    public function getIdentity();
}
