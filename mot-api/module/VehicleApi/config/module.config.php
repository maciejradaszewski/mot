<?php

use DvsaMotApi\Controller\VehicleHistoryController;
use VehicleApi\Controller\VehicleCertificateExpiryController;
use VehicleApi\Controller\VehicleController;
use VehicleApi\Controller\VehicleRetestEligibilityController;
use VehicleApi\Controller\VehicleSearchController;
use VehicleApi\Controller\VehicleDvlaController;
use DvsaMotApi\Controller\MotTestController;

return [
    'controllers' => include 'controllers.config.php',
    'router' => [
        'routes' => [
            'vehicle-search' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/vehicle-search',
                    'defaults' => [
                        'controller' => VehicleSearchController::class,
                    ],
                ],
            ],
            'vehicle-new' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/vehicle/:id',
                    'defaults' => [
                        'controller' => VehicleController::class,
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'test-in-progress-check' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/test-in-progress-check',
                            'defaults' => [
                                'controller' => MotTestController::class,
                                'action' => 'isTestInProgress',
                            ],
                        ],
                    ],
                    'test-expiry-check' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/test-expiry-check[/:isDvla]',
                            'defaults' => [
                                'controller' => VehicleCertificateExpiryController::class,
                            ],
                            'constraints' => [
                                'isDvla'    => '[0-1]',
                            ],
                        ],
                    ],
                    'test-history' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/test-history',
                            'defaults' => [
                                'controller' => VehicleHistoryController::class,
                            ],
                        ],
                    ],
                    'retest-eligibility-check' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/retest-eligibility-check/[:siteId]',
                            'defaults' => [
                                'controller' => VehicleRetestEligibilityController::class,
                            ],
                        ],
                    ],
                ],
            ],
            'vehicle-new-list' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/vehicle/list',
                    'defaults' => [
                        'controller' => VehicleController::class,
                    ],
                ],
            ],
            'vehicle-dvla' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/vehicle-dvla/:id',
                    'defaults' => [
                        'controller' => VehicleDvlaController::class,
                    ]
                ]
            ],
        ],
    ],
];
