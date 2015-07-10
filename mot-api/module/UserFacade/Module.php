<?php

namespace UserFacade;

use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Module
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
                UserFacadeLocal::class => \UserFacade\Factory\UserFacadeLocalFactory::class,
            ]
        ];
    }
}
