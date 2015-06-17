<?php

namespace NotificationApi\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use NotificationApi\Service\NotificationService;
use NotificationApi\Service\Validator\NotificationValidator;

/**
 * Class NotificationServiceFactory
 * @package NotificationApi\Factory
 */
class NotificationServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new NotificationService(
            $serviceLocator,
            new NotificationValidator()
        );
    }
}
