<?php

namespace DvsaMotEnforcement\Factory\Controller;

use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaMotEnforcement\Controller\MotTestController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Create MotTestSearchController.
 */
class MotTestControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     *
     * @return MotTestController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /* @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator     = $controllerManager->getServiceLocator();
        $paramObfuscator    = $serviceLocator->get(ParamObfuscator::class);

        return new MotTestController($paramObfuscator);
    }
}
