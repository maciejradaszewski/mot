<?php

namespace AccountApi\Factory\Controller;

use AccountApi\Controller\SecurityQuestionController;
use AccountApi\Service\SecurityQuestionService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class SecurityQuestionControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceManager $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();

        return new SecurityQuestionController($serviceLocator->get(SecurityQuestionService::class));
    }
}
