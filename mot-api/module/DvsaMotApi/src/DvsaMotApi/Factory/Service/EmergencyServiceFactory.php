<?php

namespace DvsaMotApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaMotApi\Service\EmergencyService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EmergencyServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new EmergencyService(
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('Hydrator')
        );
    }
}
