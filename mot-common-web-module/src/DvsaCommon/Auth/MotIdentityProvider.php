<?php

namespace DvsaCommon\Auth;

/**
 * Temporary class to implement MotIdentityProviderInterface. Needs to be implemented differently on each tier.
 */
class MotIdentityProvider implements MotIdentityProviderInterface
{
    /** @var  MotIdentityInterface */
    private $motIdentity;

    public function __construct($motIdentity)
    {
        $this->motIdentity = $motIdentity;
    }

    /** @return MotIdentityInterface */
    public function getIdentity()
    {
        return $this->motIdentity;
    }
}
