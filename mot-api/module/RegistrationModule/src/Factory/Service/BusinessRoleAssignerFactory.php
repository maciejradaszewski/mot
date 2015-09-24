<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Api\RegistrationModule\Factory\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Dvsa\Mot\Api\RegistrationModule\Service\BusinessRoleAssigner;
use DvsaEntities\Entity\BusinessRoleStatus;
use DvsaEntities\Entity\PersonSystemRole;
use DvsaEntities\Repository\PersonSystemRoleRepository;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class BusinessRoleAssignerFactory.
 */
class BusinessRoleAssignerFactory implements FactoryInterface
{
    /**
     * Create service.
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return BusinessRoleAssigner
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get(EntityManager::class);

        /** @var PersonSystemRoleRepository $personSystemRoleRepository */
        $personSystemRoleRepository = $entityManager->getRepository(PersonSystemRole::class);

        /** @var EntityRepository $businessRoleStatusRepository */
        $businessRoleStatusRepository = $entityManager->getRepository(BusinessRoleStatus::class);

        $service = new BusinessRoleAssigner(
            $entityManager,
            $personSystemRoleRepository,
            $businessRoleStatusRepository
        );

        return $service;
    }
}
