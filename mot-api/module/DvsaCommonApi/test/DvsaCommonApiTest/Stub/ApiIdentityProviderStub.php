<?php

namespace DvsaCommonApiTest\Stub;

use DvsaAuthentication\Identity;
use DvsaCommon\Auth\MotIdentityProviderInterface;

class ApiIdentityProviderStub implements MotIdentityProviderInterface
{
    private $identity;

    public function setIdentity(Identity $identity)
    {
        $this->identity = $identity;
    }

    /** @return Identity */
    public function getIdentity()
    {
        return $this->identity;
    }
}