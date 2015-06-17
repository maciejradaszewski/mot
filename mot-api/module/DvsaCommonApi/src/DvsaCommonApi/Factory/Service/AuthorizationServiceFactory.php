<?php

namespace DvsaCommonApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommonApi\Service\MotApiAuthorizationService;
use DvsaEntities\Repository\RbacRepository;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthorizationServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new MotApiAuthorizationService(
            $serviceLocator->get('MotIdentityProvider'),
            new RbacRepository($serviceLocator->get(EntityManager::class))
        );
    }
}
