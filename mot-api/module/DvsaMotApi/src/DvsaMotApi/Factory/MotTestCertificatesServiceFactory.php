<?php

namespace DvsaMotApi\Factory;

use DvsaMotApi\Service\CertificateStorageService;
use DvsaMotApi\Service\MotTestCertificatesService;
use Doctrine\ORM\EntityManager;
use MailerApi\Service\MailerService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaEntities\Entity\MotTestRecentCertificate;

class MotTestCertificatesServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $pdfRepo = $serviceLocator->get(EntityManager::class)->getRepository(MotTestRecentCertificate::class);
        $auth = $serviceLocator->get('DvsaAuthorisationService');
        $storageService = $serviceLocator->get(CertificateStorageService::class);
        $mailerService = $serviceLocator->get(MailerService::class);

        return new MotTestCertificatesService($pdfRepo, $auth, $storageService, $mailerService);
    }
}
