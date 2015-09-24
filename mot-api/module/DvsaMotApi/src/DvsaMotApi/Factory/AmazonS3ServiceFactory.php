<?php

namespace DvsaMotApi\Factory;

use Doctrine\ORM\EntityManager;
use DvsaMotApi\Service\AmazonS3Service;
use DvsaMotApi\Service\AmazonSDKService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class AmazonS3ServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new AmazonS3Service(
            $serviceLocator->get('Config'),
            $serviceLocator->get(AmazonSDKService::class)
        );
    }
}
