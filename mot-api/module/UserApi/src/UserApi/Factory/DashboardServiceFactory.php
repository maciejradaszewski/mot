<?php

namespace UserApi\Factory;

use Doctrine\ORM\EntityManager;
use DvsaEntities\Entity\AuthorisationForAuthorisedExaminer;
use NotificationApi\Service\NotificationService;
use SiteApi\Service\SiteService;
use UserApi\Dashboard\Service\DashboardService;
use UserApi\Person\Service\PersonalAuthorisationForMotTestingService;
use UserApi\SpecialNotice\Service\SpecialNoticeService;
use UserFacade\UserFacadeLocal;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DashboardServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get(EntityManager::class);
        return new DashboardService(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get(UserFacadeLocal::class),
            $serviceLocator->get(SiteService::class),
            $serviceLocator->get(SpecialNoticeService::class),
            $serviceLocator->get(NotificationService::class),
            $serviceLocator->get(PersonalAuthorisationForMotTestingService::class),
            $serviceLocator->get('TesterService'),
            $entityManager->getRepository(AuthorisationForAuthorisedExaminer::class)
        );
    }
}
