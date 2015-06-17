<?php

namespace DvsaMotApi\Factory\Repository;

use Doctrine\ORM\EntityManager;
use DvsaEntities\Entity\DvlaVehicle;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DvlaVehicleRepositoryFactory.
 */
class DvlaVehicleRepositoryFactory implements FactoryInterface
{
    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     *
     * @return \DvsaEntities\Repository\DvlaVehicleRepository
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $serviceLocator
            ->get(EntityManager::class)
            ->getRepository(DvlaVehicle::class);
    }
}
