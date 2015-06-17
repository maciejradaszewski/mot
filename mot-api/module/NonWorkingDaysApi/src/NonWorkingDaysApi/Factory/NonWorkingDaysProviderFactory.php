<?php

namespace NonWorkingDaysApi\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use NonWorkingDaysApi\Provider\HolidaysProvider;
use Doctrine\ORM\EntityManager;
use DvsaEntities\Entity\NonWorkingDay;

class NonWorkingDaysProviderFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get(EntityManager::class);

        return new HolidaysProvider($entityManager->getRepository(NonWorkingDay::class));
    }
}
