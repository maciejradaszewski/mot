<?php

use PersonApi\Service\PersonalDetailsService;
use PersonApi\Factory\Service\PersonalDetailsServiceFactory;
use MailerApi\Factory\MailerServiceFactory;
use MailerApi\Service\MailerService;

return [
    'factories' => [
        MailerService::class => MailerServiceFactory::class,
        PersonalDetailsService::class => PersonalDetailsServiceFactory::class,
    ],
];
