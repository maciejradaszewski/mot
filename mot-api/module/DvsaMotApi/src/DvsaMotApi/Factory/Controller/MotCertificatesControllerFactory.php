<?php

namespace DvsaMotApi\Factory\Controller;

use Doctrine\ORM\EntityManager;
use DvsaMotApi\Service\MotTestCertificatesService;
use DvsaMotApi\Controller\MotCertificatesController;
use DvsaMotApi\Controller\MotTestStatusController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class MotCertificatesControllerFactory implements FactoryInterface
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

        return new MotCertificatesController($serviceLocator->get(MotTestCertificatesService::class));
    }
}
