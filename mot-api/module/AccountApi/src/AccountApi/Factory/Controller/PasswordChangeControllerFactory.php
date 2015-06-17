<?php

namespace AccountApi\Factory\Controller;

use AccountApi\Controller\PasswordChangeController;
use AccountApi\Service\TokenService;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class PasswordChangeControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceManager $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();

        return new PasswordChangeController(
            $serviceLocator->get(TokenService::class),
            $serviceLocator->get(EntityManager::class)
        );
    }
}
