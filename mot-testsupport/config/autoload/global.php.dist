<?php

return array(
    'apiUrl' => 'http://mot-api/',

    'mot-api-module-config-cache' => '/tmp/module-config-cache.mot-api.php',
    'mot-api-module-map-cache' => '/tmp/module-classmap-cache.mot-api.php',
    'mot-web-frontend-module-config-cache' => '/tmp/module-config-cache.mot-web-frontend.php',
    'mot-web-frontend-module-map-cache' => '/tmp/module-classmap-cache.mot-web-frontend.php',

    'doctrine' => array(
        'connection'    => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params'      => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'user'     => 'motdbuser',
                    'password' => 'password',
                    'dbname'   => 'mot',
                    'charset'  => 'utf8'
                ),
            ),
        ),
        'configuration' => array(
            'orm_default' => array(
                'generate_proxies' => true,
                'proxy_dir'        => '/var/lib/vosa/DoctrineORMModule/Proxy',
                'proxy_namespace'  => 'DoctrineORMModule\Proxy',
            ),
        )
    ),
    'security' => [
        'obfuscate' => [
            'key'     => 'acjdajsd92md09282822',
            'entries' => [
                'vehicleId' => true,
            ],
        ],
    ],
    'logger' => [
        'output' => '/var/log/dvsa/mot-testsupport.log',
    ],
);
