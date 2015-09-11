<?php

namespace PersonApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use NotificationApi\Service\NotificationService;
use PersonApi\Service\PersonalAuthorisationForMotTestingService;
use PersonApi\Service\Validator\PersonalAuthorisationForMotTestingValidator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaEventApi\Service\EventService;
use PersonApi\Service\PersonService;

class PersonalAuthorisationForMotTestingServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new PersonalAuthorisationForMotTestingService(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get(NotificationService::class),
            new PersonalAuthorisationForMotTestingValidator(),
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get(EventService::class),
            $serviceLocator->get(PersonService::class)
        );
    }
}
