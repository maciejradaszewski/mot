<?php

namespace DvsaCommonApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommonApi\Service\EntityHelperService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EntityHelperServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new EntityHelperService($serviceLocator->get(EntityManager::class));
    }
}
