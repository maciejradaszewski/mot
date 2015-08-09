<?php

namespace Dvsa\Mot\Frontend\AuthenticationModule\Factory\Service;

use Dvsa\OpenAM\OpenAMClientInterface;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\IdentitySessionStateService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for IdentitySessionStateService instances.
 */
class IdentitySessionStateServiceFactory implements FactoryInterface
{
    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     *
     * @return \Dvsa\Mot\Frontend\AuthenticationModule\Service\IdentitySessionStateService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $motIdentityProvider = $serviceLocator->get('MotIdentityProvider');
        $openAMClient = $serviceLocator->get(OpenAMClientInterface::class);
        $authenticationCookieService = $serviceLocator->get('tokenService');
        $logger = $serviceLocator->get('Application/Logger');

        return new IdentitySessionStateService(
            $openAMClient,
            $motIdentityProvider,
            $authenticationCookieService,
            $logger
        );
    }
}
