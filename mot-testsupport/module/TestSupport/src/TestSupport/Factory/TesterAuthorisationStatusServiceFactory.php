<?php

namespace TestSupport\Factory;

use TestSupport\Service\TesterAuthorisationStatusService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use TestSupport\Helper\TestSupportRestClientHelper;
use Doctrine\ORM\EntityManager;

class TesterAuthorisationStatusServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new TesterAuthorisationStatusService(
            $serviceLocator->get(TestSupportRestClientHelper::class),
            $serviceLocator->get(EntityManager::class)
        );
    }
}
