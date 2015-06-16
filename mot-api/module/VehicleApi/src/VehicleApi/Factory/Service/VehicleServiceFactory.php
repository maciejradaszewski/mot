<?php

namespace VehicleApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Database\Transaction;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaEntities\Entity\DvlaVehicle;
use DvsaEntities\Entity\DvlaVehicleImportChangeLog;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\Vehicle;
use DvsaEntities\Entity\VehicleV5C;
use DvsaMotApi\Service\MotTestServiceProvider;
use DvsaMotApi\Service\Validator\VehicleValidator;
use VehicleApi\Service\VehicleService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaEntities\Entity\DvlaMakeModelMap;

/**
 * Class VehicleServiceFactory
 */
class VehicleServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $em = $serviceLocator->get(EntityManager::class);

        return new VehicleService(
            $serviceLocator->get('DvsaAuthorisationService'),
            $em->getRepository(Vehicle::class),
            $em->getRepository(VehicleV5C::class),
            $em->getRepository(DvlaVehicle::class),
            $em->getRepository(DvlaVehicleImportChangeLog::class),
            $em->getRepository(DvlaMakeModelMap::class),
            $serviceLocator->get('VehicleCatalogService'),
            new VehicleValidator(),
            $serviceLocator->get('OtpService'),
            $serviceLocator->get(ParamObfuscator::class),
            new MotTestServiceProvider($serviceLocator),
            $serviceLocator->get(MotIdentityProviderInterface::class),
            $em->getRepository(Person::class),
            new Transaction($em)
        );
    }
}
