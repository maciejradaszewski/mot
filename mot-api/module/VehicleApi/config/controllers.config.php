<?php

use DvsaMotApi\Controller\VehicleHistoryController;
use VehicleApi\Controller\VehicleCertificateExpiryController;
use VehicleApi\Controller\VehicleController;
use VehicleApi\Controller\VehicleRetestEligibilityController;
use VehicleApi\Controller\VehicleSearchController;
use VehicleApi\Controller\VehicleDvlaController;
use VehicleApi\Factory\Controller\VehicleSearchControllerFactory;
use VehicleApi\Factory\Controller\VehicleControllerFactory;

$config = [
    'invokables' => [
        VehicleRetestEligibilityController::class => VehicleRetestEligibilityController::class,
        VehicleCertificateExpiryController::class => VehicleCertificateExpiryController::class,
        DvsaMotApi\Controller\MotTest::class => DvsaMotApi\Controller\MotTestController::class,
        VehicleHistoryController::class => VehicleHistoryController::class,
        VehicleDvlaController::class => VehicleDvlaController::class,
    ],
    'factories' => [
        VehicleSearchController::class => VehicleSearchControllerFactory::class,
        VehicleController::class => VehicleControllerFactory::class
    ]
];

return $config;