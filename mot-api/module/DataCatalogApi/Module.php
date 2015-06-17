<?php
namespace DataCatalogApi;

use DataCatalogApi\Service\DataCatalogService;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Module
 *
 * @package CensorApi
 */
class Module implements AutoloaderProviderInterface,
                        ConfigProviderInterface,
                        ServiceProviderInterface
{
    public function getAutoloaderConfig()
    {
        return [
            \Zend\Loader\ClassMapAutoloader::class => [
                __DIR__ . '/autoload_classmap.php',
            ],
            \Zend\Loader\StandardAutoloader::class => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                DataCatalogService::class => \DataCatalogApi\Factory\Service\DataCatalogServiceFactory::class,
                'VehicleCatalogService'   => \DataCatalogApi\Factory\Service\VehicleCatalogServiceFactory::class,
            ]
        ];
    }
}
