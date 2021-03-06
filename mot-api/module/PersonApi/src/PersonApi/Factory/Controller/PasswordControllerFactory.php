<?php

namespace PersonApi\Factory\Controller;

use PersonApi\Controller\PasswordController;
use PersonApi\Service\PasswordService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class PasswordControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceManager $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();

        return new PasswordController(
            $serviceLocator->get(PasswordService::class)
        );
    }
}
