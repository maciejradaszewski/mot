<?php

namespace DvsaMotApi\Factory\Controller;

use DvsaMotApi\Controller\MotTestStatusController;
use DvsaMotApi\Service\CertificateCreationService;
use DvsaMotApi\Service\MotTestStatusChangeNotificationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MotTestStatusControllerFactory implements FactoryInterface
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

        return new MotTestStatusController(
            $serviceLocator->get('MotTestStatusChangeService'),
            $serviceLocator->get(CertificateCreationService::class),
            $serviceLocator->get(MotTestStatusChangeNotificationService::class)
        );
    }
}
