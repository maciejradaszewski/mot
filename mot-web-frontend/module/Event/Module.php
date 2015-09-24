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
    }
}
