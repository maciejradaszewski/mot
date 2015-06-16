<?php

namespace OrganisationApi\Factory\Controller;

use Doctrine\ORM\EntityManager;
use OrganisationApi\Controller\MotTestLogController;
use OrganisationApi\Service\MotTestLogService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MotTestLogControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();

        return new MotTestLogController(
            $serviceLocator->get(MotTestLogService::class),
            $serviceLocator->get('ElasticSearchService'),
            $serviceLocator->get(EntityManager::class)
        );
    }
}
