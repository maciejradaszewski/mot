<?php

namespace Organisation\Factory\Controller;

use DvsaClient\MapperFactory;
use Organisation\Controller\AuthorisedExaminerStatusController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;

/**
 * Class AuthorisedExaminerStatusControllerFactory.
 */
class AuthorisedExaminerStatusControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     *
     * @return AuthorisedExaminerStatusController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /* @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator    = $controllerManager->getServiceLocator();

        return new AuthorisedExaminerStatusController(
            $serviceLocator->get('AuthorisationService'),
            $serviceLocator->get(MapperFactory::class),
            $serviceLocator->get('MotIdentityProvider'),
            new Container(AuthorisedExaminerStatusController::SESSION_CNTR_KEY)
        );
    }
}
