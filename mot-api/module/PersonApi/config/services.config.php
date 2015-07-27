<?php

use PersonApi\Factory\Service\BasePersonServiceFactory;
use PersonApi\Factory\Service\DashboardServiceFactory;
use PersonApi\Factory\Service\PersonalAuthorisationForMotTestingServiceFactory;
use PersonApi\Factory\Service\PersonalDetailsServiceFactory;
use PersonApi\Factory\Service\PersonContactServiceFactory;
use PersonApi\Factory\Service\PersonServiceFactory;
use PersonApi\Factory\Service\UserStatsServiceFactory;
use PersonApi\Factory\Validator\BasePersonValidatorFactory;
use PersonApi\Generator\PersonContactGenerator;
use PersonApi\Generator\PersonGenerator;
use PersonApi\Service\BasePersonService;
use PersonApi\Service\DashboardService;
use PersonApi\Service\PersonalAuthorisationForMotTestingService;
use PersonApi\Service\PersonalDetailsService;
use PersonApi\Service\PersonContactService;
use PersonApi\Service\PersonService;
use PersonApi\Service\UserStatsService;
use PersonApi\Service\Validator\BasePersonValidator;
use PersonApi\Service\Validator\PersonalDetailsValidator;

return [
    'factories'  => [
        BasePersonService::class                         => BasePersonServiceFactory::class,
        BasePersonValidator::class                       => BasePersonValidatorFactory::class,
        PersonalAuthorisationForMotTestingService::class => PersonalAuthorisationForMotTestingServiceFactory::class,
        PersonalDetailsService::class                    => PersonalDetailsServiceFactory::class,
        PersonService::class                             => PersonServiceFactory::class,
        UserStatsService::class                          => UserStatsServiceFactory::class,
        DashboardService::class                          => DashboardServiceFactory::class,
        PersonContactService::class                      => PersonContactServiceFactory::class,
    ],
    'invokables' => [
        PersonGenerator::class          => PersonGenerator::class,
        PersonContactGenerator::class   => PersonContactGenerator::class,
        PersonalDetailsValidator::class => PersonalDetailsValidator::class,
    ],
];
