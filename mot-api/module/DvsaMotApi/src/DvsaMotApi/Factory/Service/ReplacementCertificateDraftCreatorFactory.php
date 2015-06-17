<?php

namespace DvsaMotApi\Factory\Service;

use DvsaMotApi\Service\ReplacementCertificate\ReplacementCertificateDraftCreator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ReplacementCertificateDraftCreatorFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ReplacementCertificateDraftCreator(
            $serviceLocator->get('MotTestSecurityService'),
            $serviceLocator->get('DvsaAuthorisationService')
        );
    }
}
