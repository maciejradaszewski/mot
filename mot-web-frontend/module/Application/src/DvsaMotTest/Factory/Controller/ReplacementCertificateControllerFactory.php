<?php

namespace DvsaMotTest\Factory\Controller;

use DvsaMotTest\Controller\ReplacementCertificateController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Vehicle\Service\VehicleCatalogService;

/**
 * Create ReplacementCertificateController.
 */
class ReplacementCertificateControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ReplacementCertificateController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /* @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();
        $vehicleCatalogService = $serviceLocator->get(VehicleCatalogService::class);
        return new ReplacementCertificateController($vehicleCatalogService);
    }
}
