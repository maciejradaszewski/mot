<?php

namespace DvsaMotApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaEntities\Entity\AuthorisationForTestingMot;
use DvsaEntities\Entity\AuthorisationForTestingMotStatus;
use DvsaEntities\Entity\Person;
use DvsaMotApi\Service\DemoTestAssessmentService;
use NotificationApi\Service\NotificationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaMotApi\Helper\TesterQualificationStatusChangeEventHelper;
use DvsaCommon\Date\DateTimeHolder;

class DemoTestAssessmentServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get(EntityManager::class);

        return new DemoTestAssessmentService(
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get(NotificationService::class),
            $entityManager->getRepository(Person::class),
            $entityManager->getRepository(AuthorisationForTestingMot::class),
            $entityManager->getRepository(AuthorisationForTestingMotStatus::class),
            $serviceLocator->get(TesterQualificationStatusChangeEventHelper::class),
            new DateTimeHolder()
        );
    }
}
