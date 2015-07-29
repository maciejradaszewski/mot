<?php

namespace OrganisationApi\Factory\Controller;

use Doctrine\ORM\EntityManager;
use OrganisationApi\Controller\AuthorisedExaminerStatusController;
use OrganisationApi\Service\AuthorisedExaminerStatusService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AuthorisedExaminerStatusControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();

        return new AuthorisedExaminerStatusController($serviceLocator->get(AuthorisedExaminerStatusService::class));
    }
}
