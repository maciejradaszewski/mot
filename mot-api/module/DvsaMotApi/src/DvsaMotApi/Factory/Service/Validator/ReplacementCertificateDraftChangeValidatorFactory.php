<?php

namespace DvsaMotApi\Factory\Service\Validator;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaMotApi\Service\Validator\ReplacementCertificateDraftChangeValidator;

class ReplacementCertificateDraftChangeValidatorFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ReplacementCertificateDraftChangeValidator(
            $serviceLocator->get('CensorService')
        );
    }
}
