<?php

namespace Event;

use Zend\Loader\ClassMapAutoloader;
use Zend\Loader\StandardAutoloader;

/**
 * Class Module
 *
 * @package Event
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
            ClassMapAutoloader::class => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            StandardAutoloader::class => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
