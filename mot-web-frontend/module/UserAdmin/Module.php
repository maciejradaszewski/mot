<?php

namespace UserAdmin;

use UserAdmin\Factory\Service\HelpdeskAccountAdminServiceFactory;
use UserAdmin\Factory\UserAdminSessionManagerFactory;
use UserAdmin\Service\HelpdeskAccountAdminService;
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
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                UserAdminSessionManager::class => UserAdminSessionManagerFactory::class,
                HelpdeskAccountAdminService::class => HelpdeskAccountAdminServiceFactory::class,
            ],
        ];
    }
}
