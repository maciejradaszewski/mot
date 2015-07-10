<?php

namespace NotificationApi;

use NotificationApi\Service\NotificationService;
use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\BootstrapListenerInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Module
 *
 * @package NotificationApi
 */
class Module
{
    public function getAutoloaderConfig()
    {
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories'  => [
                NotificationService::class => \NotificationApi\Factory\NotificationServiceFactory::class,
            ],
            'invokables' => [

            ]
        ];
    }
}
