<?php

namespace TestSupport\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use TestSupport\Service\TesterService;
use TestSupport\Service\AccountService;
use TestSupport\Helper\TestSupportRestClientHelper;
use Doctrine\ORM\EntityManager;

class TesterServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new TesterService(
            $serviceLocator->get(TestSupportRestClientHelper::class),
            $serviceLocator->get(AccountService::class),
            $serviceLocator->get(EntityManager::class)
        );
    }
}
