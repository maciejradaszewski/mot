<?php

namespace DvsaMotApi\Factory;

use Doctrine\ORM\EntityManager;
use DvsaMotApi\Service\AwsCredentialsProvider;
use DvsaMotApi\Service\AwsCredentialsProviderService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class AwsCredentialsProviderFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
          return new AwsCredentialsProviderService(
              $serviceLocator->get('Config')
          );
    }
}
