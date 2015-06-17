<?php

namespace Core\Service;

use DvsaCommon\Auth\MotIdentityInterface;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use Zend\Authentication\AuthenticationService;

/**
 * MotIdentityProviderInterface that delegates to Zend Framework
 */
class MotFrontendIdentityProvider implements MotIdentityProviderInterface
{

    /** @var AuthenticationService $zendAuthenticationService */
    private $zendAuthenticationService;

    /**
     * @param AuthenticationService $zendAuthenticationService
     */
    public function setZendAuthenticationService($zendAuthenticationService)
    {
        $this->zendAuthenticationService = $zendAuthenticationService;
    }

    public function __construct(AuthenticationService $zendAuthenticationService)
    {
        $this->zendAuthenticationService = $zendAuthenticationService;
    }

    /** @return MotIdentityInterface */
    public function getIdentity()
    {
        if ($this->zendAuthenticationService->hasIdentity()) {
            return $this->zendAuthenticationService->getIdentity();
        } else {
            return null;
        }
    }
}
