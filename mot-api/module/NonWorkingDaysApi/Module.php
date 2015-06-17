<?php
namespace NonWorkingDaysApi;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Module
 */
class Module implements AutoloaderProviderInterface, ServiceProviderInterface
{
    public function getAutoloaderConfig()
    {
        return [
            \Zend\Loader\StandardAutoloader::class => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                'NonWorkingDaysProvider' => \NonWorkingDaysApi\Factory\NonWorkingDaysProviderFactory::class,
                'NonWorkingDaysLookupManager' => \NonWorkingDaysApi\Factory\NonWorkingDaysLookupManagerFactory::class,
                'NonWorkingDaysHelper' => \NonWorkingDaysApi\Factory\NonWorkingDaysHelperFactory::class
            ]
        ];
    }
}
