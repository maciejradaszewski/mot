<?php

use DvsaEntities\Factory\Repository\AuthForAeStatusRepositoryFactory;
use DvsaEntities\Factory\Repository\CompanyTypeRepositoryFactory;
use DvsaEntities\Factory\Repository\OrganisationContactTypeRepositoryFactory;
use DvsaEntities\Factory\Repository\OrganisationRepositoryFactory;
use DvsaEntities\Factory\Repository\PhoneContactTypeRepositoryFactory;
use DvsaEntities\Factory\Repository\RbacRepositoryFactory;
use DvsaEntities\Factory\Repository\SiteRepositoryFactory;
use DvsaEntities\Repository\AuthForAeStatusRepository;
use DvsaEntities\Repository\CompanyTypeRepository;
use DvsaEntities\Repository\OrganisationContactTypeRepository;
use DvsaEntities\Repository\OrganisationRepository;
use DvsaEntities\Repository\PhoneContactTypeRepository;
use DvsaEntities\Repository\RbacRepository;
use DvsaEntities\Repository\SiteRepository;

return [
    'service_manager' => [
        'invokables' => [
            \DvsaEntities\Audit\EntityAuditListener::class => \DvsaEntities\Audit\EntityAuditListener::class
        ],
        'factories' => [
            RbacRepository::class => RbacRepositoryFactory::class,
            OrganisationContactTypeRepository::class => OrganisationContactTypeRepositoryFactory::class,
            CompanyTypeRepository::class => CompanyTypeRepositoryFactory::class,
            OrganisationRepository::class => OrganisationRepositoryFactory::class,
            PhoneContactTypeRepository::class => PhoneContactTypeRepositoryFactory::class,
            AuthForAeStatusRepository::class => AuthForAeStatusRepositoryFactory::class,
            SiteRepository::class => SiteRepositoryFactory::class,
        ],
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
