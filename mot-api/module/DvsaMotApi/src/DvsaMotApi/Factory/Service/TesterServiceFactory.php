<?php

namespace DvsaMotApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaMotApi\Service\TesterService;
use UserApi\SpecialNotice\Service\SpecialNoticeService;
use UserFacade\UserFacadeLocal;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class TesterServiceFactory
 * @package DvsaMotApi\Factory\Service
 */
class TesterServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new TesterService(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('Hydrator'),
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get(SpecialNoticeService::class),
            $serviceLocator->get('RoleProviderService'),
            $serviceLocator->get(MotIdentityProviderInterface::class)
        );
    }
}
