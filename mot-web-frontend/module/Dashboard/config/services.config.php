<?php

use Dashboard\Authorisation\ViewTradeRolesAssertion;
use Dashboard\Data\ApiDashboardResource;
use Dashboard\Data\ApiNotificationResource;
use Dashboard\Factory\Authorisation\ViewTradeRolesAssertionFactory;
use Dashboard\PersonStore;
use Dashboard\Service\PasswordService;
use Dashboard\Service\TradeRolesAssociationsService;
use Dashboard\Factory\Service\TradeRolesAssociationsServiceFactory;

return [
    'factories' => [
        PersonStore::class                   => \Dashboard\Factory\PersonStoreFactory::class,
        ApiNotificationResource::class       => \Dashboard\Factory\ApiNotificationResourceFactory::class,
        ApiDashboardResource::class          => \Dashboard\Factory\ApiDashboardResourceFactory::class,
        PasswordService::class               => \Dashboard\Factory\Service\PasswordServiceFactory::class,
        ViewTradeRolesAssertion::class       => ViewTradeRolesAssertionFactory::class,
        TradeRolesAssociationsService::class => TradeRolesAssociationsServiceFactory::class
    ],
];
