<?php

namespace SiteApi\Factory\Controller;

use Doctrine\ORM\EntityManager;
use SiteApi\Controller\MotTestLogController;
use SiteApi\Service\MotTestLogService;
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

