<?php

namespace DvsaMotApi\Factory\Controller;

use DvsaDocument\Service\Document\DocumentService;
use DvsaMotApi\Controller\CertificatePrintingController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class UserControllerFactory.
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

        /* @var DocumentService $documentService */
        $documentService = $serviceLocator->get('DocumentService');

        return new CertificatePrintingController($documentService, $serviceLocator->get('DvsaAuthorisationService'));
    }
}
