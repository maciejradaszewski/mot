<?php

namespace PersonApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Validator\DrivingLicenceValidator;
use PersonApi\Service\LicenceDetailsService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaCommonApi\Filter\XssFilter;

class LicenceDetailsServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new LicenceDetailsService(
            $serviceLocator->get(EntityManager::class),
            new DrivingLicenceValidator(),
            $serviceLocator->get(XssFilter::class)
        );
    }
}
