<?php

namespace VehicleApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaEntities\Entity\DvlaVehicle;
use DvsaEntities\Entity\DvlaVehicleImportChangeLog;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\Vehicle;
use VehicleApi\Service\VehicleSearchService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaCommon\Obfuscate\ParamObfuscator;

/**
 * Create instance of service VehicleSearchService
 *
 * @package DvsaMotApi\Factory\Service
 */
class VehicleSearchServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get(EntityManager::class);

        return new VehicleSearchService(
            $serviceLocator->get('DvsaAuthorisationService'),
            $entityManager->getRepository(Vehicle::class),
            $entityManager->getRepository(DvlaVehicle::class),
            $entityManager->getRepository(DvlaVehicleImportChangeLog::class),
            $entityManager->getRepository(MotTest::class),
            $serviceLocator->get('TesterService'),
            $serviceLocator->get('VehicleCatalogService'),
            $serviceLocator->get(ParamObfuscator::class)

        );
    }
}
