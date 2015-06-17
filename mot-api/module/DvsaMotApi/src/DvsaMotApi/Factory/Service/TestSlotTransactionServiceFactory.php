<?php

namespace DvsaMotApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TestSlotTransactionServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new TestSlotTransactionService(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('HttpPaymentServiceClient'),
            $serviceLocator->get('DvsaAuthorisationService')
        );
    }
}
