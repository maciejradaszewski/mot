<?php
namespace DvsaAuthentication;

use DvsaAuthentication\Listener\WebAuthenticationListener;
use Zend\Mvc\MvcEvent;


class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $app = $e->getApplication();
        $webAuthenticationListener = $app->getServiceManager()->get(WebAuthenticationListener::class);
        $app->getEventManager()->attach(MvcEvent::EVENT_ROUTE, $webAuthenticationListener, -1);
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return [
            \Zend\Loader\ClassMapAutoloader::class => [
                __DIR__ . '/autoload_classmap.php',
            ],
            \Zend\Loader\StandardAutoloader::class => [
                'namespaces' => [
                    'DvsaAuthentication' => __DIR__ . '/src/DvsaAuthentication',
                ],
            ],
        ];
    }
}
