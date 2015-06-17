<?php

namespace Application\Factory\Data;

use Application\Data\ApiCurrentMotTest;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaCommon\HttpRestJson\Client as HttpRestJsonClient;

class ApiCurrentMotTestFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ApiCurrentMotTest($serviceLocator->get(HttpRestJsonClient::class));
    }
}
