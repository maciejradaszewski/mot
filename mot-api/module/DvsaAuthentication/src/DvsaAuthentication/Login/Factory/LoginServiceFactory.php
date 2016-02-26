<?php

namespace DvsaAuthentication\Login\Factory;

use DvsaAuthentication\Login\AuthenticationResponseMapper;
use DvsaAuthentication\Login\LoginService;
use DvsaAuthentication\Login\UsernamePasswordAuthenticator;
use PersonApi\Service\PasswordExpiryService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoginServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $authenticator = $serviceLocator->get(UsernamePasswordAuthenticator::class);
        $passwordExpiryService = $serviceLocator->get(PasswordExpiryService::class);

        return new LoginService(
            $authenticator,
            $passwordExpiryService,
            new AuthenticationResponseMapper()
        );
    }
}