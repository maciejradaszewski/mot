<?php

namespace DvsaCommonApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommonApi\Service\LazyIdentityProvider;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MotIdentityProviderFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new LazyIdentityProvider(
            $serviceLocator->get('Request'),
            $serviceLocator->get(EntityManager::class)
        );
    }
}
