<?php

use UserApi\Person\Service\PersonalDetailsService;
use UserApi\Factory\PersonalDetailsServiceFactory;
use MailerApi\Factory\MailerServiceFactory;
use MailerApi\Service\MailerService;

return [
    'factories' => [
        MailerService::class => MailerServiceFactory::class,
        PersonalDetailsService::class => PersonalDetailsServiceFactory::class,
    ],
];
