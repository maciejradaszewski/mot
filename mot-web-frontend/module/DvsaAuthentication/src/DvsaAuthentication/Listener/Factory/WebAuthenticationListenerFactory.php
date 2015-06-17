<?php

namespace DvsaAuthentication\Listener\Factory;

use DvsaApplicationLogger\TokenService\TokenServiceInterface;
use DvsaAuthentication\Listener\WebAuthenticationListener;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 */
class WebAuthenticationListenerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var TokenServiceInterface $tokenService
         * @var AuthenticationService $authenticationService
         */
        $authenticationService = $serviceLocator->get('ZendAuthenticationService');
        $tokenService = $serviceLocator->get('tokenService');
        $config = $serviceLocator->get('config');
        $logger = $serviceLocator->get('Application\Logger');

        return new WebAuthenticationListener($authenticationService, $tokenService, $logger, $config);
    }
}
