<?php

namespace DvsaMotApi\Factory\Service;

use DvsaCommonApi\Authorisation\Assertion\ReadMotTestAssertion;
use DvsaMotApi\Service\OdometerReadingQueryService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OdometerReadingQueryServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new OdometerReadingQueryService(
            $serviceLocator->get('OdometerReadingDeltaAnomalyChecker'),
            $serviceLocator->get('OdometerReadingRepository'),
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get(ReadMotTestAssertion::class),
            $serviceLocator->get('MotTestRepository'),
            $serviceLocator->get('DvsaAuthenticationService')
        );
    }
}
