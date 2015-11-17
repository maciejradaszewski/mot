<?php

namespace PersonApi\Factory\Controller;

use PersonApi\Controller\PersonTradeRoleController;
use PersonApi\Service\PersonTradeRoleService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class PersonTradeRoleControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceManager $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();
        /** @var PersonTradeRoleService $service */
        $service = $serviceLocator->get(PersonTradeRoleService::class);

        return new PersonTradeRoleController($service);
    }
}
