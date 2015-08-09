<?php

namespace Dvsa\Mot\Frontend\AuthenticationModule\Factory\Service;

use Dvsa\Mot\Frontend\AuthenticationModule\Service\GotoUrlValidatorService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Dvsa\OpenAM\Options\OpenAMClientOptions;

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

        try {

            /** @var OpenAMClientOptions $openAmOptions */
            $openAmOptions = $serviceLocator->get(OpenAMClientOptions::class);
            $cookieDomain = $openAmOptions->getCookieDomain();
            $whitelistDomain = [$cookieDomain];
            return new GotoUrlValidatorService($whitelistDomain);
        }catch(\Exception $e) {
            var_dump($e->getMessage()); exit;
        }
    }
}
