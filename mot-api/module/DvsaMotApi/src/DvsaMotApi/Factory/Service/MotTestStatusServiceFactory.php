<?php

namespace DvsaMotApi\Factory\Service;

use DvsaMotApi\Service\MotTestStatusService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MotTestStatusServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new MotTestStatusService(
            $serviceLocator->get('DvsaAuthorisationService')
        );
    }
}
