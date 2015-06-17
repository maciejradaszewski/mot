<?php

namespace DvsaMotApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommonApi\Authorisation\Assertion\ReadMotTestAssertion;
use DvsaMotApi\Service\MotTestService;
use OrganisationApi\Service\OrganisationService;
use VehicleApi\Service\VehicleService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory for MotTestService
 */
class MotTestServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new MotTestService(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('MotTestValidator'),
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get('TesterService'),
            $serviceLocator->get('RetestEligibilityValidator'),
            $serviceLocator->get('ConfigurationRepository'),
            $serviceLocator->get('MotTestMapper'),
            $serviceLocator->get('OtpService'),
            $serviceLocator->get(OrganisationService::class),
            $serviceLocator->get(VehicleService::class),
            $serviceLocator->get(ReadMotTestAssertion::class)
        );
    }
}
