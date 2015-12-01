<?php

namespace DvsaMotApi\Factory\Service;

use DvsaMotApi\Service\RoleRefreshService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RoleRefreshServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new RoleRefreshService([]);
    }
}
