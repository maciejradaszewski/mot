<?php

namespace DataCatalogApi\Factory\Service;

use DataCatalogApi\Service\VehicleCatalogService;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class VehicleCatalogServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $vehicleCatalogService = new VehicleCatalogService($serviceLocator->get(EntityManager::class));

        return $vehicleCatalogService;
    }
}
