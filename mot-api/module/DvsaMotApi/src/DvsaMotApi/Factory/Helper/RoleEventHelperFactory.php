<?php

namespace DvsaMotApi\Factory\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\EntityManager;
use DvsaMotApi\Helper\RoleEventHelper;
use DvsaCommon\Date\DateTimeHolder;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaEventApi\Service\EventService;
use DvsaEntities\Entity\EventPersonMap;

class RoleEventHelperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get(EntityManager::class);

        return new RoleEventHelper(
            $serviceLocator->get(MotIdentityProviderInterface::class),
            $serviceLocator->get(EventService::class),
            $entityManager->getRepository(EventPersonMap::class),
            new DateTimeHolder()
        );
    }
}
