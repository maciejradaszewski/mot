<?php

namespace DvsaMotApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaRbac\Service\RoleService;
use DvsaEntities\Repository\RbacRepository;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RoleServiceFactory
 * @package DvsaMotApi\Factory\Service
 */
class RoleServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new RoleService(
            $serviceLocator->get(EntityManager::class),
            new RbacRepository($serviceLocator->get(EntityManager::class))
        );
    }
}
