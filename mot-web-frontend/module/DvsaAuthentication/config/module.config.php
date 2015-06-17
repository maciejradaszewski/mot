<?php

use DvsaAuthentication\Controller as Authentication;
use DvsaAuthentication\Listener\Factory\WebAuthenticationListenerFactory;
use DvsaAuthentication\Listener\WebAuthenticationListener;
use DvsaAuthentication\Service\Factory\WebAccessTokenServiceFactory;

return [
    'controllers'                => [
        'invokables' => [
            Authentication\AuthenticationController::class => Authentication\AuthenticationController::class,
        ],
    ],
    'router'                     => [
        'routes' => [
            'logout' => [
                'type'    => 'segment',
                'options' => [
                    'route'    => '/logout',
                    'defaults' => [
                        'controller' => Authentication\AuthenticationController::class,
                        'action'     => 'logout',
                    ],
                ],
            ],
        ],
    ],
    'service_manager'            => [
        'factories' => [
            'AuthAdapter'                => \Application\Factory\AuthAdapterFactory::class,
            'ZendAuthenticationService'  => \Application\Factory\ZendAuthenticationServiceFactory::class,
            'tokenService'               => WebAccessTokenServiceFactory::class,
            WebAuthenticationListener::class => WebAuthenticationListenerFactory::class
        ],
        'abstract_factories' => [
            \Zend\Cache\Service\StorageCacheAbstractServiceFactory::class,
            \Zend\Log\LoggerAbstractServiceFactory::class,
        ],
        'aliases'            => [
            'translator' => 'MvcTranslator',
            \Zend\Authentication\AuthenticationService::class => 'ZendAuthenticationService'
        ]
    ],
    'session_namespace_prefixes' => [
        'DvsaMotEnforcement\\Session\\'
    ],
    'session'                    => [
        'remember_me_seconds' => 2419200,
        'use_cookies'         => true,
        'cookie_httponly'     => true,
    ],
    'controller_plugins'         => [
        'invokables' => [
            'ajaxResponse' => \Dvsa\Mot\Frontend\Plugin\AjaxResponsePlugin::class,
        ]
    ],
    'view_manager'               => [

        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies'          => [
            'ViewJsonStrategy',
        ],
    ],
    'module_layouts'             => [
        'DvsaAuthentication' => 'application/layout',
    ],
];
