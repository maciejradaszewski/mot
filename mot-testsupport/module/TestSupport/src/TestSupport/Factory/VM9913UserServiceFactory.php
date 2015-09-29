<?php

namespace TestSupport\Factory;

use TestSupport\Service\VM9913UserService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use TestSupport\Service\AccountDataService;

class VM9913UserServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $accountService = $serviceLocator->get(AccountDataService::class);
        $service = new VM9913UserService($accountService);
        return $service;
    }
}
