<?php

namespace UserApi\Factory;

use Doctrine\ORM\EntityManager;
use UserApi\Dashboard\Service\UserStatsService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserStatsServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new UserStatsService(
            $serviceLocator->get(EntityManager::class)
        );
    }
}
