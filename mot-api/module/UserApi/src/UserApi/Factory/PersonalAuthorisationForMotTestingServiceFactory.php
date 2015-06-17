<?php

namespace UserApi\Factory;

use Doctrine\ORM\EntityManager;
use NotificationApi\Service\NotificationService;
use UserApi\Person\Service\PersonalAuthorisationForMotTestingService;
use UserApi\Person\Service\Validator\PersonalAuthorisationForMotTestingValidator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PersonalAuthorisationForMotTestingServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new PersonalAuthorisationForMotTestingService(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get(NotificationService::class),
            new PersonalAuthorisationForMotTestingValidator()
        );
    }
}
