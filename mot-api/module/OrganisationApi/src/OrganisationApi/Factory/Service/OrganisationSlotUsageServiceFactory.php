<?php

namespace OrganisationApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaEntities\Entity\Site;
use OrganisationApi\Service\OrganisationSlotUsageService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OrganisationSlotUsageServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new OrganisationSlotUsageService(
            $serviceLocator->get(EntityManager::class)->getRepository(Site::class),
            $serviceLocator->get('DvsaAuthorisationService')
        );
    }
}
