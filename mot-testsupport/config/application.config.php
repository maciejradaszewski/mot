<?php

$path = getenv('APPLICATION_CONFIG_PATH') ?: dirname(__DIR__) . '/config/autoload';

return [
    // This should be an array of module namespaces used in the application.
    'modules'                 => [
        'TestSupport',
        'DoctrineModule',
        'DoctrineORMModule',
        'DvsaFeature'
    ],
    'module_listener_options' => [
        'module_paths'      => [
            dirname(__DIR__) . '/module',
            dirname(__DIR__) . '/vendor',
        ],
        'config_glob_paths' => [
            sprintf('%s/{,*.}{global,local}.php', $path)
        ],

    ],
];
