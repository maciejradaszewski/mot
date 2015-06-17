<?php

namespace DvsaMotApi\Factory\Service;

use DvsaMotApi\Service\MotTestDateHelper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MotTestDateHelperFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new MotTestDateHelper(
            $serviceLocator->get('CertificateExpiryService')
        );
    }
}
