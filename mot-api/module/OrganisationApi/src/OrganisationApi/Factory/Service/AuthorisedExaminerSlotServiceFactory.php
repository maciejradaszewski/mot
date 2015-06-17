<?php

namespace OrganisationApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaEntities\Entity\Organisation;
use OrganisationApi\Service\AuthorisedExaminerSlotService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AuthorisedExaminerSlotServiceFactory
 * @package OrganisationApi\Factory\Service
 */
class AuthorisedExaminerSlotServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new AuthorisedExaminerSlotService(
            $serviceLocator->get(EntityManager::class)->getRepository(Organisation::class)
        );
    }
}
