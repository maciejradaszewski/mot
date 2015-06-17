<?php

namespace DvsaMotApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaMotApi\Service\TesterExpiryService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TesterExpiryServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new TesterExpiryService(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get('ConfigurationRepository')
        );
    }
}
