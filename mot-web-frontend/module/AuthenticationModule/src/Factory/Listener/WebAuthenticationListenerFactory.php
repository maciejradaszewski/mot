<?php

namespace Dvsa\Mot\Frontend\AuthenticationModule\Factory\Listener;

use DvsaApplicationLogger\TokenService\TokenServiceInterface;
use Dvsa\Mot\Frontend\AuthenticationModule\Listener\WebAuthenticationListener;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for WebAuthenticationListener instances.
 */
class WebAuthenticationListenerFactory implements FactoryInterface
{
    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     *
     * @return \Dvsa\Mot\Frontend\AuthenticationModule\Listener\WebAuthenticationListener
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var AuthenticationService */
        $authenticationService = $serviceLocator->get('ZendAuthenticationService');
        /** @var TokenServiceInterface */
        $tokenService = $serviceLocator->get('tokenService');
        $logger = $serviceLocator->get('Application\Logger');

        return new WebAuthenticationListener($authenticationService, $tokenService, $logger);
    }
}
