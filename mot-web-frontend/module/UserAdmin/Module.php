<?php

namespace UserAdmin;

use UserAdmin\Factory\Service\HelpdeskAccountAdminServiceFactory;
use UserAdmin\Factory\Service\TesterQualificationStatusServiceFactory;
use UserAdmin\Factory\UserAdminSessionManagerFactory;
use UserAdmin\Service\HelpdeskAccountAdminService;
use UserAdmin\Service\TesterQualificationStatusService;
use UserAdmin\Service\UserAdminSessionManager;

/**
 * Class Module.
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

    public function getServiceConfig()
    {
        return [
            'factories' => [
                UserAdminSessionManager::class => UserAdminSessionManagerFactory::class,
                HelpdeskAccountAdminService::class => HelpdeskAccountAdminServiceFactory::class,
                TesterQualificationStatusService::class => TesterQualificationStatusServiceFactory::class,
            ],
        ];
    }
}
