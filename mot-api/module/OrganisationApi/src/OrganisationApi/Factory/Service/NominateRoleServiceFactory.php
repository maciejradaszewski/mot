<?php

namespace OrganisationApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Database\Transaction;
use DvsaEntities\Entity\BusinessRoleStatus;
use DvsaEntities\Entity\Organisation;
use DvsaEntities\Entity\OrganisationBusinessRole;
use DvsaEntities\Entity\OrganisationPositionHistory;
use DvsaEntities\Entity\Person;
use OrganisationApi\Model\Operation\DirectNominationOperation;
use OrganisationApi\Model\Operation\NominateByRequestOperation;
use OrganisationApi\Service\NominateRoleService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class NominateRoleServiceFactory
 * @package OrganisationApi\Factory\Service
 */
class NominateRoleServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get(EntityManager::class);

        return new NominateRoleService(
            $entityManager->getRepository(Organisation::class),
            $entityManager->getRepository(OrganisationPositionHistory::class),
            $entityManager->getRepository(Person::class),
            $entityManager->getRepository(OrganisationBusinessRole::class),
            $entityManager->getRepository(BusinessRoleStatus::class),
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get(NominateByRequestOperation::class),
            $serviceLocator->get(DirectNominationOperation::class),
            new Transaction($serviceLocator->get(\Doctrine\ORM\EntityManager::class)),
            $serviceLocator->get('DvsaAuthenticationService')
        );
    }
}
