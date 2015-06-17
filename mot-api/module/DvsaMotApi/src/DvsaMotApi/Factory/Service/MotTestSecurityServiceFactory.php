<?php

namespace DvsaMotApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommonApi\Authorisation\Assertion\ReadMotTestAssertion;
use DvsaMotApi\Service\MotTestSecurityService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MotTestSecurityServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new MotTestSecurityService(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('DvsaAuthenticationService'),
            $serviceLocator->get('TesterService'),
            $serviceLocator->get('ConfigurationRepository'),
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get('MotTestRepository'),
            $serviceLocator->get(ReadMotTestAssertion::class)
        );
    }
}
