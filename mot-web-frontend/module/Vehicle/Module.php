<?php

namespace Vehicle;

/**
 * Class Module.
 */
class Module
{
    /**
     * {@inheritdoc}
     */
    public function getConfig()
    {
        return require __DIR__ . '/config/module.config.php';
    }

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
}
