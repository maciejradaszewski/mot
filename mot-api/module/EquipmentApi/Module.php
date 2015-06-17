<?php
namespace EquipmentApi;

use EquipmentApi\Service\EquipmentModelService;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Module
 */
class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            \Zend\Loader\ClassMapAutoloader::class => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            \Zend\Loader\StandardAutoloader::class => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                EquipmentModelService::class => \EquipmentApi\Factory\Service\EquipmentModelServiceFactory::class,
            ],
        ];
    }
}
