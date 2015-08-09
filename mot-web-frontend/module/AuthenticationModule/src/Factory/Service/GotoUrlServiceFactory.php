<?php

namespace Dvsa\Mot\Frontend\AuthenticationModule\Factory\Service;

use Dvsa\Mot\Frontend\AuthenticationModule\Service\GotoUrlService;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\GotoUrlValidatorService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for GotoUrlService.
 */
class GotoUrlServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return GotoUrlService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $gotoUrlValidator = $serviceLocator->get(GotoUrlValidatorService::class);

        return new GotoUrlService($gotoUrlValidator);
    }
}
