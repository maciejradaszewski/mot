<?php

namespace Vehicle\Factory;

use DvsaCommon\Obfuscate\ParamObfuscator;
use Vehicle\Controller\VehicleController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Create VehicleController.
 */
class VehicleControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     *
     * @return VehicleController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /* @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator     = $controllerManager->getServiceLocator();
        $paramObfuscator    = $serviceLocator->get(ParamObfuscator::class);

        return new VehicleController($paramObfuscator);
    }
}
