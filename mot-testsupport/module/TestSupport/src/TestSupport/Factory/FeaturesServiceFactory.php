<?php
namespace TestSupport\Factory;

use TestSupport\Service\FeaturesService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FeaturesServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new FeaturesService(
            $serviceLocator->get(FeaturesService::class)
        );
    }
}