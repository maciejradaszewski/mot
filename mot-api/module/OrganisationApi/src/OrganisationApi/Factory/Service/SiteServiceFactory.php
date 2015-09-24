<?php

namespace OrganisationApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Date\DateTimeHolder;
use DvsaEntities\Entity\Organisation;
use DvsaEntities\Entity\OrganisationSiteMap;
use DvsaEntities\Entity\OrganisationSiteStatus;
use DvsaEntities\Entity\Site;
use DvsaEventApi\Service\EventService;
use NotificationApi\Service\NotificationService;
use OrganisationApi\Service\Mapper\SiteMapper;
use OrganisationApi\Service\SiteService;
use OrganisationApi\Service\Validator\SiteLinkValidator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SiteServiceFactory
 *
 * @package OrganisationApi\Factory\Service
 */
class SiteServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new SiteService(
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get(EntityManager::class)->getRepository(Organisation::class),
            new SiteMapper()
        );
    }
}
