<?php

namespace TestSupport\Factory;

use DvsaCommon\HttpRestJson\Client;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use TestSupport\Service\AedmService;
use TestSupport\Service\AccountDataService;
use TestSupport\Service\AccountService;
use Doctrine\ORM\EntityManager;

class AedmServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new AedmService(
            $serviceLocator->get(AccountDataService::class),
            $serviceLocator->get(AccountService::class),
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get(Client::class)
        );
    }
}
