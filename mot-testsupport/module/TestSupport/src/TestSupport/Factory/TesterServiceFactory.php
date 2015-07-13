<?php

namespace TestSupport\Factory;

use TestSupport\Service\TesterAuthorisationStatusService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use TestSupport\Service\TesterService;
use TestSupport\Service\AccountService;
use TestSupport\Helper\TestSupportRestClientHelper;
use TestSupport\Helper\NotificationsHelper;
use TestSupport\Helper\SitePermissionsHelper;
use Doctrine\ORM\EntityManager;

class TesterServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new TesterService(
            $serviceLocator->get(TestSupportRestClientHelper::class),
            $serviceLocator->get(NotificationsHelper::class),
            $serviceLocator->get(SitePermissionsHelper::class),
            $serviceLocator->get(AccountService::class),
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get(TesterAuthorisationStatusService::class)
        );
    }
}
