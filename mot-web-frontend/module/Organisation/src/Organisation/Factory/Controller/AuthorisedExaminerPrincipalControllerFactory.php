<?php

namespace Organisation\Factory\Controller;

use Organisation\Controller\AuthorisedExaminerPrincipalController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthorisedExaminerPrincipalControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     *
     * @return AuthorisedExaminerPrincipalController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /* @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();

        return new AuthorisedExaminerPrincipalController($serviceLocator->get('AuthorisationService'));
    }
}