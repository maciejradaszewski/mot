<?php

namespace UserApi\Person\Factory\Controller;

use DvsaCommon\Validator\UsernameValidator;
use UserApi\Person\Controller\PersonByLoginController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class PersonByLoginControllerFactory.
 */
class PersonByLoginControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     *
     * @return SpecialNoticesController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /* @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator    = $controllerManager->getServiceLocator();
        $usernameValidator = $serviceLocator->get(UsernameValidator::class);

        return new PersonByLoginController($usernameValidator);
    }
}
