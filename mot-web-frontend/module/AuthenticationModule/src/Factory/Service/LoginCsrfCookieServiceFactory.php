<?php

namespace Dvsa\Mot\Frontend\AuthenticationModule\Factory\Service;

use Dvsa\Mot\Frontend\AuthenticationModule\Service\LoginCsrfCookieService;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use DvsaCommon\Configuration\MotConfig;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class LoginCsrfCookieServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $motConfig = $serviceLocator->get(MotConfig::class);

        return new LoginCsrfCookieService($motConfig);
    }
}
