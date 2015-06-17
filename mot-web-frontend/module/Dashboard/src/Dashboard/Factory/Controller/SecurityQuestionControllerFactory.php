<?php

namespace Dashboard\Factory\Controller;

use Dashboard\Controller\SecurityQuestionController;
use Account\Service\SecurityQuestionService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class SecurityQuestionControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        $appServiceLocator = $controllerManager->getServiceLocator();

        /** @var SecurityQuestionService */
        $service = $appServiceLocator->get(SecurityQuestionService::class);

        $controller = new SecurityQuestionController($service);

        return $controller;
    }
}