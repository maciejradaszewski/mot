<?php

namespace DvsaMotApi\Factory\Service;

use DvsaCommonApi\Authorisation\Assertion\ApiPerformMotTestAssertion;
use DvsaMotApi\Service\OdometerReadingUpdatingService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OdometerReadingUpdatingServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new OdometerReadingUpdatingService(
            $serviceLocator->get('OdometerReadingRepository'),
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get('MotTestSecurityService'),
            $serviceLocator->get('MotTestValidator'),
            $serviceLocator->get(ApiPerformMotTestAssertion::class)
        );
    }
}
