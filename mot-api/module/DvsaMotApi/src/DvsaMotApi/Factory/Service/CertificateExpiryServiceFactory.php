<?php

namespace DvsaMotApi\Factory\Service;

use DvsaMotApi\Service\CertificateExpiryService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaCommon\Date\DateTimeHolder;
use DvsaCommon\Obfuscate\ParamObfuscator;

/**
 * Class CertificateExpiryServiceFactory.
 */
class CertificateExpiryServiceFactory implements FactoryInterface
{
    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \DvsaMotApi\Service\CertificateExpiryService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new CertificateExpiryService(
            new DateTimeHolder(),
            $serviceLocator->get('MotTestRepository'),
            $serviceLocator->get('VehicleRepository'),
            $serviceLocator->get('DvlaVehicleRepository'),
            $serviceLocator->get('ConfigurationRepository'),
            $serviceLocator->get('DvsaAuthorisationService')
        );
    }
}
