<?php

namespace DvsaMotApi\Factory\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\EntityManager;
use DvsaMotApi\Helper\RoleNotificationHelper;
use NotificationApi\Service\NotificationService;

class RoleNotificationHelperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new RoleNotificationHelper(
            $serviceLocator->get(NotificationService::class)
        );
    }
}

