<?php

namespace Dvsa\Mot\Frontend\AuthenticationModule\Factory\Controller;

use Dvsa\Mot\Frontend\AuthenticationModule\Controller\SecurityController;
use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\OpenAMAuthenticator;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\GotoUrlService;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\IdentitySessionStateService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\SessionManager;

/**
 * Factory for SecurityController instances.
 */
class SecurityControllerFactory implements FactoryInterface
{
    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     *
     * @return \Dvsa\Mot\Frontend\AuthenticationModule\Controller\SecurityController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator = $serviceLocator->getServiceLocator();
        $request = $serviceLocator->get('Request');
        $authenticator = $serviceLocator->get(OpenAMAuthenticator::class);
        $gotoUrlService = $serviceLocator->get(GotoUrlService::class);
        $authenticationCookieService = $serviceLocator->get('tokenService');
        $identitySessionStateService = $serviceLocator->get(IdentitySessionStateService::class);
        $authenticationService = $serviceLocator->get('ZendAuthenticationService');

        return new SecurityController(
            $request,
            $authenticator,
            $gotoUrlService,
            $authenticationCookieService,
            $identitySessionStateService,
            $authenticationService,
            new SessionManager()
        );
    }
}
