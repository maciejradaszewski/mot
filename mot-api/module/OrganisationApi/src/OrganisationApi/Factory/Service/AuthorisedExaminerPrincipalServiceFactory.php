<?php

namespace OrganisationApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaEntities\Entity\Organisation;
use OrganisationApi\Service\AuthorisedExaminerPrincipalService;
use UserApi\Person\Service\BasePersonService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AuthorisedExaminerPrincipalServiceFactory
 * @package OrganisationApi\Factory\Service
 */
class AuthorisedExaminerPrincipalServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new AuthorisedExaminerPrincipalService(
            $serviceLocator->get(EntityManager::class)->getRepository(Organisation::class),
            $serviceLocator->get(BasePersonService::class),
            $serviceLocator->get('DvsaAuthorisationService')
        );
    }
}
