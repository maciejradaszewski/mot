<?php

namespace Application\Factory\Data;

use Application\Data\ApiPersonalDetails;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaCommon\HttpRestJson\Client as HttpRestJsonClient;

class ApiPersonalDetailsFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new ApiPersonalDetails($serviceLocator->get(HttpRestJsonClient::class));
    }
}
