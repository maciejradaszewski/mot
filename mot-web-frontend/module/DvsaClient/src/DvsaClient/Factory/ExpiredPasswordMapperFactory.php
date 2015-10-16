<?php

namespace DvsaClient\Factory;

use DvsaClient\Mapper\ExpiredPasswordMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaCommon\HttpRestJson\Client;

class ExpiredPasswordMapperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var Client $client */
        $client = $serviceLocator->get(Client::class);

        return new ExpiredPasswordMapper($client);
    }
}
