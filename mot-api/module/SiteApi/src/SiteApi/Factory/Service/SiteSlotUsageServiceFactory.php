<?php

namespace SiteApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaEntities\Entity\Site;
use SiteApi\Service\SiteSlotUsageService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SiteSlotUsageServiceFactory
 *
 * @package SiteApi\Factory\Service
 */
class SiteSlotUsageServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new SiteSlotUsageService(
            $serviceLocator->get(EntityManager::class)->getRepository(Site::class),
            $serviceLocator->get('DvsaAuthorisationService')
        );
    }
}
