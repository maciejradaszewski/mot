<?php

namespace IntegrationApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\Vehicle;
use IntegrationApi\DvlaVehicle\Service\DvlaVehicleUpdatedService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DvlaVehicleUpdatedServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EntityManager $em */
        $em = $serviceLocator->get(EntityManager::class);

        return new DvlaVehicleUpdatedService(
            $em->getRepository(MotTest::class),
            $em->getRepository(Vehicle::class),
            $serviceLocator->get("ReplacementCertificateService")
        );
    }
}
