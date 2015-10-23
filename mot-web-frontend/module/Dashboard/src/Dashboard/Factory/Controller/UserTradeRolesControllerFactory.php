<?php

namespace Dashboard\Factory\Controller;

use Application\Data\ApiPersonalDetails;
use Dashboard\Controller\UserTradeRolesController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class UserTradeRolesControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceManager $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();

        return new UserTradeRolesController(
            $serviceLocator->get(ApiPersonalDetails::class),
            $serviceLocator->get('CatalogService'),
            $serviceLocator->get('MotIdentityProvider')
        );
    }
}
