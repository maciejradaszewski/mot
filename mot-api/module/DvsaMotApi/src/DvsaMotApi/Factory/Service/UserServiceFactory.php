<?php

namespace DvsaMotApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaMotApi\Service\UserService;
use UserFacade\UserFacadeLocal;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserServiceFactory.
 */
class UserServiceFactory implements FactoryInterface
{
    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     *
     * @return \DvsaMotApi\Service\UserService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new UserService(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('Hydrator'),
            $serviceLocator->get(UserFacadeLocal::class),
            $serviceLocator->get('RoleProviderService'),
            $serviceLocator->get('DvsaAuthorisationService')
        );
    }
}
