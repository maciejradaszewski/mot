<?php

namespace DvsaAuthorisation;

use DvsaAuthorisation\Factory\AuthorisationServiceFactory;
use DvsaAuthorisation\Factory\RoleProviderServiceFactory;
use DvsaAuthorisation\Factory\SiteBusinessRoleServiceFactory;
use DvsaAuthorisation\Factory\UserRoleServiceFactory;

/**
 * Class Module
 *
 * @package DvsaAuthentication
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
            'factories' => [
                'DvsaAuthorisationService' => AuthorisationServiceFactory::class,
                'SiteBusinessRoleService' => SiteBusinessRoleServiceFactory::class,
                'RoleProviderService' => RoleProviderServiceFactory::class,
                'UserRoleService' => UserRoleServiceFactory::class,
            ]
        ];
    }
}
