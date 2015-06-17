<?php

namespace DvsaMotApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaMotApi\Service\MotTestTypeService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MotTestTypeServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new MotTestTypeService(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('Hydrator'),
            $serviceLocator->get('DvsaAuthorisationService')
        );
    }
}
