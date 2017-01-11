<?php

$env = getenv('TEST_APPLICATION_CONFIG_PATH');

if (!$env) {
    $env = getenv('APPLICATION_CONFIG_PATH');
}

$path = $env ?: dirname(__DIR__) . '/config/autoload';

return [
    // This should be an array of module namespaces used in the application.
    'modules'                 => [
        'TestSupport',
        'DoctrineModule',
        'DoctrineORMModule',
        'DvsaEntities',
        'DvsaFeature'
    ],
    'module_listener_options' => [
        'module_paths'      => [
            dirname(__DIR__) . '/module',
            dirname(__DIR__) . '/vendor',
            dirname(__DIR__) . '/../mot-api/module'
        ],
        'config_glob_paths' => [
            sprintf('%s/{,*.}{global,local}.php', $path)
        ],

    ],
];
