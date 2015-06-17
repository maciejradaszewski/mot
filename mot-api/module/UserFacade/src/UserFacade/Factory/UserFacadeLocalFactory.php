<?php

namespace UserFacade\Factory;

use Doctrine\ORM\EntityManager;
use UserFacade\UserFacadeLocal;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserFacadeLocalFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new UserFacadeLocal($serviceLocator->get(EntityManager::class));
    }
}
