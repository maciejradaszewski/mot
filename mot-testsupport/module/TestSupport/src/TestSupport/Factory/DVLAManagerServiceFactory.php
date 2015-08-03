<?php

namespace TestSupport\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use TestSupport\Service\DVLAManagerService;
use TestSupport\Service\AccountDataService;

class DVLAManagerServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new DVLAManagerService(
            $serviceLocator->get(AccountDataService::class)
        );
    }
}
