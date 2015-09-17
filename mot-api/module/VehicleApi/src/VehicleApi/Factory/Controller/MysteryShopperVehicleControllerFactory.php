<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace VehicleApi\Factory\Controller;

use VehicleApi\Controller\MysteryShopperVehicleController;
use VehicleApi\Service\MysteryShopperVehicleService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class MysteryShopperVehicleControllerFactory.
 */
class MysteryShopperVehicleControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     *
     * @return MysteryShopperVehicleController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceManager $sm */
        $sm = $controllerManager->getServiceLocator();

        /** @var MysteryShopperVehicleService $MysteryShopperVehicleService */
        $mysteryShopperVehicleService = $sm->get(MysteryShopperVehicleService::class);

        /** @var MysteryShopperVehicleController $controller */
        $controller = new MysteryShopperVehicleController($mysteryShopperVehicleService);

        return $controller;
    }
}
