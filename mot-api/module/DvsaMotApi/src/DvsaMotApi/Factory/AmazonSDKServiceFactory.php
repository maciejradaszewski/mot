<?php

namespace DvsaMotApi\Factory;

use Doctrine\ORM\EntityManager;
use DvsaMotApi\Service\AmazonSDKService;
use DvsaMotApi\Service\AwsCredentialsProvider;
use DvsaMotApi\Service\AwsCredentialsProviderService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AmazonSDKServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new AmazonSDKService(
            $serviceLocator->get('Config'),
            $serviceLocator->get(AwsCredentialsProviderService::class)
        );
    }
}
