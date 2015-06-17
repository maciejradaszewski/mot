<?php

namespace TestSupport;

use Doctrine\ORM\EntityManager;
use TestSupport\Service\JsonErrorHandlingListener;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Basic logic for module
 */
class Module implements AutoloaderProviderInterface, ConfigProviderInterface, ServiceProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $application   = $e->getApplication();
        $sm            = $application->getServiceManager();
        $sharedManager = $application->getEventManager()->getSharedManager();

        $sharedManager->attach(
            'Zend\Mvc\Application', MvcEvent::EVENT_DISPATCH_ERROR,
            function ($e) use ($sm) {
                if ($e->getParam('exception')) {
                    $sm->get('ApplicationLog')->crit($e->getParam('exception'));
                }
            }
        );

        $application->getEventManager()->attach($sm->get(JsonErrorHandlingListener::class));
    }

    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                __DIR__ . '/autoload_classmap.php',
            ],
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    'TestSupport' => __DIR__ . '/src/TestSupport',
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
        return include __DIR__ . '/config/services.config.php';
    }
}
