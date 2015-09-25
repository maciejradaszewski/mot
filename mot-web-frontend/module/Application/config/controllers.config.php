<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

use Application\Controller\FormsController;
use Application\Controller\ManualsAndGuidesController;
use Application\Controller\ReportController;
use Application\Factory\Controller\ManualsAndGuidesControllerFactory;
use DvsaMotEnforcement\Controller\MotTestController as EnforcementMotTestController;
use DvsaMotEnforcement\Controller\MotTestSearchController as EnforcementMotTestSearchController;
use DvsaMotEnforcement\Controller\ReinspectionReportController;
use DvsaMotEnforcement\Factory\Controller\MotTestControllerFactory as EnforcementMotTestControllerFactory;
use DvsaMotEnforcement\Factory\MotTestSearchControllerFactory as EnforcementMotTestSearchControllerFactory;
use DvsaMotEnforcementApi\Controller\MotTestApiController;
use DvsaMotTest\Controller\BrakeTestResultsController;
use DvsaMotTest\Controller\CertificatePrintingController;
use DvsaMotTest\Controller\ContingencyMotTestController;
use DvsaMotTest\Controller\LocationSelectController;
use DvsaMotTest\Controller\MotTestCertificatesController;
use DvsaMotTest\Controller\MotTestController;
use DvsaMotTest\Controller\MotTestOptionsController;
use DvsaMotTest\Controller\RefuseToTestController;
use DvsaMotTest\Controller\ReplacementCertificateController;
use DvsaMotTest\Controller\SpecialNoticesController;
use DvsaMotTest\Controller\StartTestConfirmationController;
use DvsaMotTest\Controller\TesterMotTestLogController;
use DvsaMotTest\Controller\TestItemSelectorController;
use DvsaMotTest\Controller\VehicleDictionaryController;
use DvsaMotTest\Controller\VehicleSearchController;
use DvsaMotTest\Factory\Controller\CertificatePrintingControllerFactory;
use DvsaMotTest\Factory\Controller\MotTestCertificatesControllerFactory;
use DvsaMotTest\Factory\Controller\RefuseToTestControllerFactory;
use DvsaMotTest\Factory\Controller\ReplacementCertificateControllerFactory;
use DvsaMotTest\Factory\Controller\SpecialNoticesControllerFactory;
use DvsaMotTest\Factory\Controller\StartTestConfirmationControllerFactory;
use DvsaMotTest\Factory\Controller\TesterMotTestLogControllerFactory;
use DvsaMotTest\Factory\Controller\VehicleSearchControllerFactory;
use DvsaMotTest\NewVehicle\Controller\CreateVehicleController;
use DvsaMotTest\NewVehicle\Controller\Factory\CreateVehicleControllerFactory;

return [
    'invokables' => [
        FormsController::class              => FormsController::class,
        ReportController::class             => ReportController::class,
        LocationSelectController::class     => LocationSelectController::class,
        MotTestController::class            => MotTestController::class,
        MotTestOptionsController::class     => MotTestOptionsController::class,
        VehicleDictionaryController::class  => VehicleDictionaryController::class,
        BrakeTestResultsController::class   => BrakeTestResultsController::class,
        TestItemSelectorController::class   => TestItemSelectorController::class,
        ContingencyMotTestController::class => ContingencyMotTestController::class,
        MotTestApiController::class         => MotTestApiController::class,
        ReinspectionReportController::class => ReinspectionReportController::class,
    ],
    'factories' => [
        ManualsAndGuidesController::class         => ManualsAndGuidesControllerFactory::class,
        RefuseToTestController::class             => RefuseToTestControllerFactory::class,
        SpecialNoticesController::class           => SpecialNoticesControllerFactory::class,
        VehicleSearchController::class            => VehicleSearchControllerFactory::class,
        StartTestConfirmationController::class    => StartTestConfirmationControllerFactory::class,
        EnforcementMotTestSearchController::class => EnforcementMotTestSearchControllerFactory::class,
        CreateVehicleController::class            => CreateVehicleControllerFactory::class,
        EnforcementMotTestController::class       => EnforcementMotTestControllerFactory::class,
        TesterMotTestLogController::class         => TesterMotTestLogControllerFactory::class,
        ReplacementCertificateController::class   => ReplacementCertificateControllerFactory::class,
        CertificatePrintingController::class      => CertificatePrintingControllerFactory::class,
        ReplacementCertificateController::class   => ReplacementCertificateControllerFactory::class,
        MotTestCertificatesController::class      => MotTestCertificatesControllerFactory::class,
    ],
];
