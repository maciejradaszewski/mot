<?php

namespace DvsaMotApi\Factory\Controller;

use Doctrine\ORM\EntityManager;
use DvsaMotApi\Controller\MotCertificatePdfController;
use DvsaMotApi\Controller\MotTestStatusController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaMotApi\Service\CertificateStorageService;


class MotCertificatePdfControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     *
     * @return MotTestStatusController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /* @var ServiceLocatorInterface $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();
        $storageService = $serviceLocator->get(CertificateStorageService::class);

        return new MotCertificatePdfController($storageService);
    }
}
