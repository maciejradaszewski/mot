<?php

namespace UserApi\Factory;

use Doctrine\ORM\EntityManager;
use DvsaMotApi\Service\UserService;
use UserApi\Application\Service\ApplicationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ApplicationServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ApplicationService(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get(UserService::class)
        );
    }
}
