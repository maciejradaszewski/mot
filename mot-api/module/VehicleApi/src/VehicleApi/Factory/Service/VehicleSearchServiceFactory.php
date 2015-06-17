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
        try {
            $config = $serviceLocator->get('config');
            $vehicleSearchFuzzyEnabled = $this->getVehicleSearchFuzzyEnabled($config);

            return new VehicleSearchService(
                $serviceLocator->get('DvsaAuthorisationService'),
                $entityManager->getRepository(Vehicle::class),
                $entityManager->getRepository(DvlaVehicle::class),
                $entityManager->getRepository(DvlaVehicleImportChangeLog::class),
                $entityManager->getRepository(MotTest::class),
                $serviceLocator->get('TesterService'),
                $serviceLocator->get('VehicleCatalogService'),
                $serviceLocator->get(ParamObfuscator::class),
                $vehicleSearchFuzzyEnabled
            );
        } catch (\Exception $e) {
        }
    }

    private function getVehicleSearchFuzzyEnabled($config)
    {
        if (isset($config['feature_toggle'])) {
            if (array_key_exists('vehicleSearchFuzzyEnabled', $config['feature_toggle'])) {
                return $config['feature_toggle']['vehicleSearchFuzzyEnabled'];
            }
        }

        return false;
    }

}
