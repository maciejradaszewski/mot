<?php

namespace PersonApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaEntities\Entity\OrganisationBusinessRoleMap;
use DvsaEntities\Entity\SiteBusinessRoleMap;
use PersonApi\Service\PersonTradeRoleService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PersonTradeRoleServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get(EntityManager::class);

        return new PersonTradeRoleService(
            $entityManager->getRepository(OrganisationBusinessRoleMap::class),
            $entityManager->getRepository(SiteBusinessRoleMap::class)
        );
    }
}
