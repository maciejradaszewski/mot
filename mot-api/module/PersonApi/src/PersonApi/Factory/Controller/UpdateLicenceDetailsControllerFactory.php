<?php

namespace PersonApi\Factory\Controller;

use PersonApi\Service\LicenceDetailsService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use PersonApi\Controller\UpdateLicenceDetailsController;
use Zend\ServiceManager\ServiceManager;

class UpdateLicenceDetailsControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     *
     * @return UpdateLicenceDetailsController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceManager $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();
        /** @var LicenceDetailsService $licenceDetailsService */
        $licenceDetailsService = $serviceLocator->get(LicenceDetailsService::class);

        return new UpdateLicenceDetailsController($licenceDetailsService);
    }
}
