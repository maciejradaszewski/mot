<?php

namespace DvsaMotTest\Factory\Controller;

use DvsaMotTest\Controller\MotTestCertificatesController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Create ReplacementCertificateController.
 */
class MotTestCertificatesControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return MotTestCertificatesController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /* @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();
        return new MotTestCertificatesController(
            $serviceLocator->get('MotTestCertificatesService'),
            $serviceLocator->get('LoggedInUserManager'),
            $serviceLocator->get('Application'),
            $serviceLocator->get('LocationSelectContainerHelper'),
            $serviceLocator->get('AuthorisationService')
        );
    }
}
