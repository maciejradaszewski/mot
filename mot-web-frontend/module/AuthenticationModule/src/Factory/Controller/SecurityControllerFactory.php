<?php

namespace Dvsa\Mot\Frontend\AuthenticationModule\Factory\Controller;

use Account\Service\ExpiredPasswordService;
use Dvsa\Mot\Frontend\AuthenticationModule\Controller\SecurityController;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\AuthenticationFailureViewModelBuilder;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\GotoUrlService;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\IdentitySessionStateService;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\LoginCsrfCookieService;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\LoginService;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\WebLoginService;
use Zend\Authentication\AuthenticationService;
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
        $response = $serviceLocator->get('Response');
        $gotoUrlService = $serviceLocator->get(GotoUrlService::class);
        $authenticationCookieService = $serviceLocator->get('tokenService');
        $identitySessionStateService = $serviceLocator->get(IdentitySessionStateService::class);
        $expiredPasswordService = $serviceLocator->get(ExpiredPasswordService::class);
        $loginCsrfCookieService = $serviceLocator->get(LoginCsrfCookieService::class);
        $authenticationService = $serviceLocator->get('ZendAuthenticationService');
        $loginService = $serviceLocator->get(WebLoginService::class);
        $authenticationFailureViewModelBuilder = $serviceLocator->get(AuthenticationFailureViewModelBuilder::class);

        return new SecurityController(
            $request,
            $response,
            $gotoUrlService,
            $authenticationCookieService,
            $identitySessionStateService,
            $loginService,
            new SessionManager(),
            $expiredPasswordService,
            $loginCsrfCookieService,
            $authenticationService,
            $authenticationFailureViewModelBuilder
        );
    }
}
