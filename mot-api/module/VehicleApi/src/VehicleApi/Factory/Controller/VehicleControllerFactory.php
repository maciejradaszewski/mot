<?php

namespace VehicleApi\Factory\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use VehicleApi\Service\VehicleService;
use VehicleApi\Service\VehicleSearchService;
use VehicleApi\Controller\VehicleController;

/**
 * Create instance of service VehicleSearchService
 *
 * @package DvsaMotApi\Factory\Service
 */
class VehicleControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $controllerManager)
    {

        $sl = $controllerManager->getServiceLocator();

        $vehicleService = $sl->get(VehicleService::class);
        $vehicleSearchService = $sl->get(VehicleSearchService::class);
        $controller = new VehicleController($vehicleService, $vehicleSearchService);

        return $controller;

    }
}
