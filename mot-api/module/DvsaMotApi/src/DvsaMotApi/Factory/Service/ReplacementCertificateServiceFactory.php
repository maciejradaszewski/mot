<?php

namespace DvsaMotApi\Factory\Service;

use DvsaAuthentication\Service\OtpService;
use DvsaMotApi\Service\CertificateCreationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaMotApi\Service\ReplacementCertificate\ReplacementCertificateService;

class ReplacementCertificateServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ReplacementCertificateService(
            $serviceLocator->get('ReplacementCertificateDraftRepository'),
            $serviceLocator->get('ReplacementCertificateDraftCreator'),
            $serviceLocator->get('ReplacementCertificateDraftUpdater'),
            $serviceLocator->get('ReplacementCertificateUpdater'),
            $serviceLocator->get('CertificateReplacementRepository'),
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get('MotTestRepository'),
            $serviceLocator->get(OtpService::class),
            $serviceLocator->get(CertificateCreationService::class)
        );
    }
}
