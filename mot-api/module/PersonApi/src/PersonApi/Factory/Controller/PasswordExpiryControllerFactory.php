<?php

namespace PersonApi\Factory\Controller;

use PersonApi\Controller\PasswordExpiryController;
use PersonApi\Service\PasswordExpiryService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class PasswordExpiryControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceManager $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();

        return new PasswordExpiryController(
            $serviceLocator->get(PasswordExpiryService::class)
        );
    }
}
