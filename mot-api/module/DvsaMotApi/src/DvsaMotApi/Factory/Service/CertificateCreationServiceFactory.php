<?php

namespace DvsaMotApi\Factory\Service;

use DataCatalogApi\Service\DataCatalogService;
use DvsaMotApi\Service\CertificateCreationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CertificateCreationServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new CertificateCreationService(
            $serviceLocator->get('MotTestService'),
            $serviceLocator->get('DocumentService'),
            $serviceLocator->get(DataCatalogService::class)
        );
    }
}
