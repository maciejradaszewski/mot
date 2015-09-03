<?php

namespace DvsaMotTest\Factory\Controller;

use DvsaMotTest\Controller\CertificatePrintingController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaMotTest\Service\CertificatePrintingService;

/**
 * Create VehicleSearchController.
 */
class CertificatePrintingControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     *
     * @return CertificatePrintingController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /* @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();
        $certificatePrintingService = $serviceLocator->get(CertificatePrintingService::class);

        return new CertificatePrintingController($certificatePrintingService);
    }
}
