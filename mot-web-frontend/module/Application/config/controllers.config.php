<?php

use DvsaMotEnforcement\Controller\MotTestController as EnforcementMotTestController;
use DvsaMotEnforcement\Controller\MotTestSearchController as EnforcementMotTestSearchController;
use DvsaMotEnforcement\Factory\Controller\MotTestControllerFactory as EnforcementMotTestControllerFactory;
use DvsaMotEnforcement\Factory\MotTestSearchControllerFactory as EnforcementMotTestSearchControllerFactory;
use DvsaMotTest\Controller\RefuseToTestController;
use DvsaMotTest\Controller\ReplacementCertificateController;
use DvsaMotTest\Controller\SpecialNoticesController;
use DvsaMotTest\Controller\StartTestConfirmationController;
use DvsaMotTest\Controller\VehicleSearchController;
use DvsaMotTest\Controller\RetestVehicleSearchController;
use DvsaMotTest\Controller\TesterMotTestLogController;
use DvsaMotTest\Factory\Controller\RefuseToTestControllerFactory;
use DvsaMotTest\Factory\Controller\ReplacementCertificateControllerFactory;
use DvsaMotTest\Factory\Controller\SpecialNoticesControllerFactory;
use DvsaMotTest\Factory\Controller\StartTestConfirmationControllerFactory;
use DvsaMotTest\Factory\Controller\VehicleSearchControllerFactory;
use DvsaMotTest\Factory\Controller\RetestVehicleSearchControllerFactory;
use DvsaMotTest\Factory\Controller\TesterMotTestLogControllerFactory;
use DvsaMotTest\NewVehicle\Controller\CreateVehicleController;
use DvsaMotTest\NewVehicle\Controller\Factory\CreateVehicleControllerFactory;

return [
    'invokables' => [
        'Application\Controller\ManualsAndGuidesController' => Application\Controller\ManualsAndGuidesController::class,
        'Application\Controller\FormsController' => Application\Controller\FormsController::class,
        'Application\Controller\ReportController' => Application\Controller\ReportController::class,
        'DvsaMotTest\Controller\LocationSelectController' => DvsaMotTest\Controller\LocationSelectController::class,
        'DvsaMotTest\Controller\MotTestController' => DvsaMotTest\Controller\MotTestController::class,
        'DvsaMotTest\Controller\MotTestOptionsController' => DvsaMotTest\Controller\MotTestOptionsController::class,
        'DvsaMotTest\Controller\VehicleDictionaryController' => DvsaMotTest\Controller\VehicleDictionaryController::class,
        'DvsaMotTest\Controller\BrakeTestResultsController' => DvsaMotTest\Controller\BrakeTestResultsController::class,
        'DvsaMotTest\Controller\TestItemSelectorController' => DvsaMotTest\Controller\TestItemSelectorController::class,
        'DvsaMotTest\Controller\ContingencyMotTestController' => DvsaMotTest\Controller\ContingencyMotTestController::class,
        'DvsaMotEnforcementApi\Controller\VehicleTestingStationFullApiController' => DvsaMotEnforcementApi\Controller\VehicleTestingStationFullApiController::class,
        'DvsaMotEnforcementApi\Controller\VehicleTestingStationApiController' => DvsaMotEnforcementApi\Controller\VehicleTestingStationApiController::class,
        'DvsaMotEnforcementApi\Controller\MotTestApiController' => DvsaMotEnforcementApi\Controller\MotTestApiController::class,
        'DvsaMotEnforcement\Controller\VehicleTestingStationFullSearchController' => DvsaMotEnforcement\Controller\VehicleTestingStationFullSearchController::class,
        'DvsaMotEnforcement\Controller\ReinspectionReportController' => DvsaMotEnforcement\Controller\ReinspectionReportController::class,
    ],
    'factories' => [
        RefuseToTestController::class => RefuseToTestControllerFactory::class,
        SpecialNoticesController::class => SpecialNoticesControllerFactory::class,
        VehicleSearchController::class => VehicleSearchControllerFactory::class,
        StartTestConfirmationController::class => StartTestConfirmationControllerFactory::class,
        EnforcementMotTestSearchController::class => EnforcementMotTestSearchControllerFactory::class,
        CreateVehicleController::class            => CreateVehicleControllerFactory::class,
        EnforcementMotTestController::class       => EnforcementMotTestControllerFactory::class,
        TesterMotTestLogController::class         => TesterMotTestLogControllerFactory::class,
        ReplacementCertificateController::class => ReplacementCertificateControllerFactory::class
    ],
];
