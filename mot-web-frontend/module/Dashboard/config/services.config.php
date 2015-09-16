<?php

use Dashboard\Data\ApiDashboardResource;
use Dashboard\Data\ApiNotificationResource;
use Dashboard\PersonStore;
use Dashboard\Service\PasswordService;

return [
    'factories' => [
        PersonStore::class             => \Dashboard\Factory\PersonStoreFactory::class,
        ApiNotificationResource::class => \Dashboard\Factory\ApiNotificationResourceFactory::class,
        ApiDashboardResource::class    => \Dashboard\Factory\ApiDashboardResourceFactory::class,
        PasswordService::class         => \Dashboard\Factory\Service\PasswordServiceFactory::class,
    ],
];