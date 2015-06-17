<?php

namespace Site;

use Site\Service\SiteSearchService;
use Site\Factory\Service\SiteSearchServiceFactory;

/**
 * Class Module
 *
 * @package Site
 */
class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                SiteSearchService::class => SiteSearchServiceFactory::class,
            ]
        ];
    }

    public function getAutoloaderConfig()
    {
        return [
            \Zend\Loader\ClassMapAutoloader::class => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            \Zend\Loader\StandardAutoloader::class => [
                'namespaces' => [
                    'Organisation' => __DIR__ . '/../Organisation/src/Organisation',
                    'Site'         => __DIR__ . '/src/Site',
                    'Application'  => __DIR__ . '/../Application/src/Application',
                    'DvsaMotTest'  => __DIR__ . '/../Application/src/DvsaMotTest',
                ],
            ],
        ];
    }
}
