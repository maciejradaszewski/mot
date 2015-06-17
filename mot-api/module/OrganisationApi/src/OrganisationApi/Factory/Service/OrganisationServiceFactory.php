<?php

namespace OrganisationApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaEntities\Entity\CompanyType;
use DvsaEntities\Entity\Organisation;
use DvsaEntities\Entity\OrganisationType;
use OrganisationApi\Service\Mapper\OrganisationMapper;
use OrganisationApi\Service\OrganisationService;
use OrganisationApi\Service\Validator\OrganisationValidator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class OrganisationServiceFactory
 * @package OrganisationApi\Factory\Service
 */
class OrganisationServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new OrganisationService(
            $serviceLocator->get(EntityManager::class),
            new OrganisationValidator(),
            $serviceLocator->get(EntityManager::class)->getRepository(Organisation::class),
            new OrganisationMapper(
                $serviceLocator->get(EntityManager::class)->getRepository(OrganisationType::class),
                $serviceLocator->get(EntityManager::class)->getRepository(CompanyType::class)
            )
        );
    }
}
