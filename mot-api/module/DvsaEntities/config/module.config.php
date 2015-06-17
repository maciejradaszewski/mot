<?php

return [
    'service_manager' => [
        'invokables' => [
            \DvsaEntities\Audit\EntityAuditListener::class => \DvsaEntities\Audit\EntityAuditListener::class
        ]
    ],
    'doctrine'        => [
        'eventmanager'  => [
            'orm_default' => [
                'subscribers' => [
                    \DvsaEntities\Audit\EntityAuditListener::class
                ],
            ],
        ],
        'driver'        => [
            'entities'    => [
                'class' => \Doctrine\ORM\Mapping\Driver\AnnotationDriver::class,
                'cache' => 'array',
                'paths' => [
                    __DIR__ . '/../src/DvsaEntities/Entity'
                ]
            ],
            'orm_default' => [
                'drivers' => [
                    'DvsaEntities\Entity' => 'entities',
                ]
            ]
        ],
        'configuration' => [
            'orm_default' => [
                'types'            => [
                    'datetime'                => \Doctrine\DBAL\Types\VarDateTimeType::class,
                    'datetimemicro'           => \DvsaEntities\Type\DateTimeMicroType::class,
                    'Time'                    => \DvsaEntities\Type\TimeType::class,
                ],
                'string_functions' => [
                    'REGEXP' => \DvsaEntities\CustomDql\Functions\Regexp::class,
                    'DATE'   => \DvsaEntities\CustomDql\Functions\Date::class,
                    'YEAR'   => \DvsaEntities\CustomDql\Functions\Year::class
                ]
            ],
        ],
    ],
];
