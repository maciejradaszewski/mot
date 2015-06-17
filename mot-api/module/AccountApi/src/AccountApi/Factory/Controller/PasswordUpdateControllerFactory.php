<?php

namespace AccountApi\Factory\Controller;

use AccountApi\Controller\PasswordUpdateController;
use AccountApi\Service\TokenService;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class PasswordUpdateControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceManager $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();

        return new PasswordUpdateController(
            $serviceLocator->get(TokenService::class),
            $serviceLocator->get(EntityManager::class)
        );
    }
}
