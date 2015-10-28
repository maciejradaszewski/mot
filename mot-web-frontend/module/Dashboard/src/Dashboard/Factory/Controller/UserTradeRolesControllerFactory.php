<?php

namespace Dashboard\Factory\Controller;

use Dashboard\Authorisation\ViewTradeRolesAssertion;
use Dashboard\Controller\UserTradeRolesController;
use Dashboard\Service\TradeRolesAssociationsService;
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
            $serviceLocator->get('MotIdentityProvider'),
            $serviceLocator->get(TradeRolesAssociationsService::class),
            $serviceLocator->get(ViewTradeRolesAssertion::class)
        );
    }
}
