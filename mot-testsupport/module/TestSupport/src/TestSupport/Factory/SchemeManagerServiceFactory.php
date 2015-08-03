<?php

namespace TestSupport\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use TestSupport\Service\SchemeManagerService;
use TestSupport\Service\AccountDataService;

class SchemeManagerServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new SchemeManagerService(
            $serviceLocator->get(AccountDataService::class)
        );
    }
}
