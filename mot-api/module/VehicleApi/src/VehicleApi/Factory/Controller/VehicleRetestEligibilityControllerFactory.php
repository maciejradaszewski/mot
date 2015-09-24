<?php

namespace VehicleApi\Factory\Controller;

use DvsaMotApi\Service\Validator\RetestEligibility\RetestEligibilityValidator;
use VehicleApi\Controller\VehicleRetestEligibilityController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Create instance of controller VehicleRetestEligibilityController
 */
class VehicleRetestEligibilityControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     *
     * @return VehicleRetestEligibilityController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceLocatorInterface $sl */
        $sl = $controllerManager->getServiceLocator();

        /** @var RetestEligibilityValidator $validator */
        $validator = $sl->get(RetestEligibilityValidator::class);

        return new VehicleRetestEligibilityController($validator);
    }
}
