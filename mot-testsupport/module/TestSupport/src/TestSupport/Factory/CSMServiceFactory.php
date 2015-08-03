<?php

namespace TestSupport\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use TestSupport\Service\CSMService;
use TestSupport\Service\AccountDataService;

class CSMServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new CSMService(
            $serviceLocator->get(AccountDataService::class)
        );
    }
}
