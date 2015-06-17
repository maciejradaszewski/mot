<?php

namespace DvsaMotApi\Factory\Service\Mapper;

use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaMotApi\Service\Mapper\MotTestMapper;
use DvsaMotApi\Service\MotTestDateHelper;
use VehicleApi\Service\VehicleSearchService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Create an instance of MotTestMapper
 *
 * @package DvsaMotApi\Factory\Service\Mapper
 */
class MotTestMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new MotTestMapper(
            $serviceLocator->get('Hydrator'),
            $serviceLocator->get('BrakeTestResultService'),
            $serviceLocator->get(VehicleSearchService::class),
            $serviceLocator->get('CertificateExpiryService'),
            $serviceLocator->get('MotTestStatusService'),
            $serviceLocator->get(MotTestDateHelper::class),
            $serviceLocator->get(ParamObfuscator::class)
        );
    }
}
