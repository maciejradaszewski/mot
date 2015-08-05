<?php

namespace DvsaCommonApi\Factory\Service;

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
            $serviceLocator->get(RbacRepository::class)
        );
    }
}
