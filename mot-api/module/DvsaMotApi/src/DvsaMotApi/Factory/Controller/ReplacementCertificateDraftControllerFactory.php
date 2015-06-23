<?php

namespace DvsaMotApi\Factory\Controller;

use DvsaMotApi\Controller\ReplacementCertificateDraftController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaMotApi\Service\CertificateCreationService;

class ReplacementCertificateDraftControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     *
     * @return ReplacementCertificateDraftControllerFactory
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /* @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();

        return new ReplacementCertificateDraftController(
            $serviceLocator->get('ReplacementCertificateService'),
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get(CertificateCreationService::class),
            $serviceLocator->get('MotTestService')
        );
    }
}
