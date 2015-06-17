<?php

namespace AccountApi\Factory\Controller;

use AccountApi\Controller\ValidateUsernameController;
use UserApi\Person\Service\PersonService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class ValidateUsernameControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceManager $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();

        return new ValidateUsernameController($serviceLocator->get(PersonService::class));
    }
}
