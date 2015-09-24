<?php
namespace TestSupport\Factory;

use TestSupport\Service\FeaturesService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FeaturesServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $path = __DIR__ . '/../../../../../../mot-web-frontend/config/autoload/features.local.php';

        return new FeaturesService(
            $path
        );
    }
}