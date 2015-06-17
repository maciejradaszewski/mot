<?php

use Dashboard\Data\ApiDashboardResource;
use Dashboard\Data\ApiNotificationResource;
use Dashboard\PersonStore;

return [
    'factories' => [
        PersonStore::class             => \Dashboard\Factory\PersonStoreFactory::class,
        ApiNotificationResource::class => \Dashboard\Factory\ApiNotificationResourceFactory::class,
        ApiDashboardResource::class    => \Dashboard\Factory\ApiDashboardResourceFactory::class
    ],
];