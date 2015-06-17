<?php

namespace IntegrationApi\Factory;

use Doctrine\ORM\EntityManager;
use IntegrationApi\OpenInterface\Repository\OpenInterfaceMotTestRepository as OpenInterfaceMotTestRepository;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OpenInterfaceMotTestRepositoryFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new OpenInterfaceMotTestRepository($serviceLocator->get(EntityManager::class));
    }
}
