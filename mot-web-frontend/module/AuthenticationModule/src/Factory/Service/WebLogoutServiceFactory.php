<?php

namespace Dvsa\Mot\Frontend\AuthenticationModule\Factory\Service;

use Dvsa\Mot\Frontend\AuthenticationModule\Service\WebAuthenticationCookieService;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\WebLogoutService;
use Dvsa\OpenAM\OpenAMClientInterface;
use Zend\Log\LoggerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for WebLogoutService instances.
 */
class WebLogoutServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return WebLogoutService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var OpenAMClientInterface $client */
        $client = $serviceLocator->get(OpenAMClientInterface::class);
        /** @var WebAuthenticationCookieService $cookieService */
        $cookieService = $serviceLocator->get('tokenService');
        /** @var LoggerInterface $logger */
        $logger = $serviceLocator->get('Application\Logger');
        /** @var \Zend\Session\SessionManager $sessionManager */
        $sessionManager = $serviceLocator->get('Zend\Session\SessionManager');

        return new WebLogoutService($client, $cookieService, $sessionManager, $logger);
    }
}
