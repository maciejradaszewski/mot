<?php

namespace Dashboard;


use Zend\EventManager\Event;
use Zend\Feed\PubSubHubbub\HttpResponse;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Module setup for Dashboard
 */
class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            \Zend\Loader\ClassMapAutoloader::class => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            \Zend\Loader\StandardAutoloader::class => [
                'namespaces' => [
                    'Dashboard' => __DIR__ . '/src/Dashboard',
                ],
            ],
        ];
    }

    public function getServiceConfig()
    {
        return include __DIR__ . '/config/services.config.php';
    }
}
