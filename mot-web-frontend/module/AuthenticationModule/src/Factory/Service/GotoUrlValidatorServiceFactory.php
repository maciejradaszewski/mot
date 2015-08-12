<?php

namespace Dvsa\Mot\Frontend\AuthenticationModule\Factory\Service;

use Dvsa\Mot\Frontend\AuthenticationModule\Service\GotoUrlValidatorService;
use Dvsa\OpenAM\Options\OpenAMClientOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for GotoUrlValidatorService.
 */
class GotoUrlValidatorServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return GotoUrlValidatorService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var OpenAMClientOptions $openAmOptions */
        $openAmOptions = $serviceLocator->get(OpenAMClientOptions::class);
        $cookieDomain = $openAmOptions->getCookieDomain();
        $whitelistDomain = [$cookieDomain];

        return new GotoUrlValidatorService($whitelistDomain);
    }
}
