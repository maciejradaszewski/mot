<?php

use PersonApi\Service\PersonalDetailsService;
use PersonApi\Factory\Service\PersonalDetailsServiceFactory;
use MailerApi\Factory\Service\MailerServiceFactory;
use MailerApi\Factory\Service\TemplateResolverServiceFactory;
use MailerApi\Service\MailerService;
use MailerApi\Service\TemplateResolverService;

return [
    'factories' => [
        MailerService::class => MailerServiceFactory::class,
        TemplateResolverService::class => TemplateResolverServiceFactory::class,
        PersonalDetailsService::class => PersonalDetailsServiceFactory::class,
    ],
];
