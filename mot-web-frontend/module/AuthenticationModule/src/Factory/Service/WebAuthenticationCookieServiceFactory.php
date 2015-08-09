<?php

namespace Dvsa\Mot\Frontend\AuthenticationModule\Factory\Service;

use Dvsa\OpenAM\Options\OpenAMClientOptions;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\WebAuthenticationCookieService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for WebAuthenticationCookieService instances.
 */
class WebAuthenticationCookieServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var OpenAMClientOptions $openAMOptions */
        $openAMOptions = $serviceLocator->get(OpenAMClientOptions::class);
        $request = $serviceLocator->get('Request');
        $response = $serviceLocator->get('Response');

        return new WebAuthenticationCookieService($request, $response, $openAMOptions);
    }
}
