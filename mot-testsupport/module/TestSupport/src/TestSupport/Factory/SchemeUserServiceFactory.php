<?php

namespace TestSupport\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use TestSupport\Service\SchemeUserService;
use TestSupport\Service\AccountDataService;

class SchemeUserServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new SchemeUserService(
            $serviceLocator->get(AccountDataService::class)
        );
    }
}
