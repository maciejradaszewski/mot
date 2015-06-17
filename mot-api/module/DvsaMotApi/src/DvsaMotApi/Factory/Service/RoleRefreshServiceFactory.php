<?php

namespace DvsaMotApi\Factory\Service;

use DvsaMotApi\Service\RoleRefreshService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaMotApi\Service\RoleRefresher\TesterActiveRoleRefresher;

class RoleRefreshServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new RoleRefreshService(
            [
                new TesterActiveRoleRefresher($serviceLocator->get('TesterService'))
            ]
        );
    }
}
