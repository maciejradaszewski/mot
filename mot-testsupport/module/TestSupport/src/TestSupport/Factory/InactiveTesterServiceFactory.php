<?php

namespace TestSupport\Factory;

use TestSupport\Service\InactiveTesterService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use TestSupport\Service\TesterService;
use Doctrine\ORM\EntityManager;

class InactiveTesterServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new InactiveTesterService(
            $serviceLocator->get(TesterService::class),
            $serviceLocator->get(EntityManager::class)
        );
    }
}
