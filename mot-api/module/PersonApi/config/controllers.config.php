<?php

use PersonApi\Controller\AuthorisedExaminerController;
use PersonApi\Controller\DashboardController;
use PersonApi\Controller\MotTestingAuthorisationController;
use PersonApi\Controller\PersonalDetailsController;
use PersonApi\Controller\PersonAuthorisationController;
use PersonApi\Controller\PersonByLoginController;
use PersonApi\Controller\PersonContactController;
use PersonApi\Controller\PersonController;
use PersonApi\Controller\PersonCurrentMotTestController;
use PersonApi\Controller\PersonProfileRestrictedController;
use PersonApi\Controller\PersonProfileUnrestrictedController;
use PersonApi\Controller\PersonSiteCountController;
use PersonApi\Controller\ResetClaimAccountController;
use PersonApi\Controller\ResetPinController;
use PersonApi\Controller\UserStatsController;
use PersonApi\Factory\Controller\AuthorisedExaminerControllerFactory;
use PersonApi\Factory\Controller\DashboardControllerFactory;
use PersonApi\Factory\Controller\MotTestingAuthorisationControllerFactory;
use PersonApi\Factory\Controller\PersonalDetailsControllerFactory;
use PersonApi\Factory\Controller\PersonAuthorisationControllerFactory;
use PersonApi\Factory\Controller\PersonByLoginControllerFactory;
use PersonApi\Factory\Controller\PersonContactControllerFactory;
use PersonApi\Factory\Controller\PersonControllerFactory;
use PersonApi\Factory\Controller\PersonCurrentMotTestControllerFactory;
use PersonApi\Factory\Controller\PersonProfileRestrictedControllerFactory;
use PersonApi\Factory\Controller\PersonProfileUnrestrictedControllerFactory;
use PersonApi\Factory\Controller\PersonSiteCountControllerFactory;
use PersonApi\Factory\Controller\ResetClaimAccountControllerFactory;
use PersonApi\Factory\Controller\ResetPinControllerFactory;
use PersonApi\Factory\Controller\UserStatsControllerFactory;

return [
    'factories' => [
        AuthorisedExaminerController::class        => AuthorisedExaminerControllerFactory::class,
        DashboardController::class                 => DashboardControllerFactory::class,
        MotTestingAuthorisationController::class   => MotTestingAuthorisationControllerFactory::class,
        PersonalDetailsController::class           => PersonalDetailsControllerFactory::class,
        PersonAuthorisationController::class       => PersonAuthorisationControllerFactory::class,
        PersonByLoginController::class             => PersonByLoginControllerFactory::class,
        PersonController::class                    => PersonControllerFactory::class,
        PersonContactController::class             => PersonContactControllerFactory::class,
        PersonCurrentMotTestController::class      => PersonCurrentMotTestControllerFactory::class,
        PersonProfileRestrictedController::class   => PersonProfileRestrictedControllerFactory::class,
        PersonProfileUnrestrictedController::class => PersonProfileUnrestrictedControllerFactory::class,
        PersonSiteCountController::class           => PersonSiteCountControllerFactory::class,
        ResetClaimAccountController::class         => ResetClaimAccountControllerFactory::class,
        ResetPinController::class                  => ResetPinControllerFactory::class,
        UserStatsController::class                 => UserStatsControllerFactory::class,
    ],
];
