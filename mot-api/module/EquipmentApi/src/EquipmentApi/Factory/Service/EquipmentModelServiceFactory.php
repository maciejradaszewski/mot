<?php

namespace EquipmentApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaEntities\Entity\EquipmentModel;
use EquipmentApi\Service\EquipmentModelService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class EquipmentModelServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new EquipmentModelService(
            $serviceLocator->get(EntityManager::class)->getRepository(EquipmentModel::class),
            $serviceLocator->get('DvsaAuthorisationService')
        );
    }
}
