<?php
namespace Session;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;

/**
 * Class Module
 * @package Session
 * @codeCoverageIgnore This class only returns config: no point in testing
 */
class Module implements AutoloaderProviderInterface, ServiceProviderInterface, ConfigProviderInterface
{
    public function getAutoloaderConfig()
    {
        return [
            \Zend\Loader\ClassMapAutoloader::class => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            \Zend\Loader\StandardAutoloader::class => [
                'namespaces' => [
                    'Session' => __DIR__ . '/src/Session',
                ],
            ],
        ];
    }

    public function getServiceConfig()
    {
        return include __DIR__ . '/config/services.config.php';
    }
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
