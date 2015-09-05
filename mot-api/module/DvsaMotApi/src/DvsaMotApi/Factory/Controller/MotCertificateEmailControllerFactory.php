<?php

namespace DvsaMotApi\Factory\Controller;

use Doctrine\ORM\EntityManager;
use DvsaMotApi\Controller\MotCertificateEmailController;
use DvsaMotApi\Controller\MotCertificatePdfController;
use DvsaMotApi\Controller\MotTestStatusController;
use DvsaMotApi\Service\MotTestCertificatesService;
use DvsaMotApiTest\Service\MotTestCertificatesServiceTest;
use MailerApi\Service\MailerService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaMotApi\Service\CertificateStorageService;


class MotCertificateEmailControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     *
     * @return MotCertificateEmailController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        return new MotCertificateEmailController($controllerManager->getServiceLocator()->get(MotTestCertificatesService::class));
    }
}
