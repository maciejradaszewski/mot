<?php

use SiteApi\Controller\DefaultBrakeTestsController;
use SiteApi\Controller\EquipmentController;
use SiteApi\Controller\MotTestInProgressController;
use SiteApi\Controller\SiteContactController;
use SiteApi\Controller\VehicleTestingStationAuthorisedClassesController;
use SiteApi\Controller\SiteController;
use SiteApi\Controller\SitePositionController;
use SiteApi\Controller\SitePositionValidateController;
use SiteApi\Controller\SiteRoleController;
use SiteApi\Controller\SiteSlotUsageController;
use SiteApi\Controller\SiteTestingDailyScheduleController;
use SiteApi\Factory\Controller\SiteSearchControllerFactory;

return [
    'controllers' => [
        'invokables' => [
            VehicleTestingStationAuthorisedClassesController::class =>
                VehicleTestingStationAuthorisedClassesController::class,
            SiteController::class                     => SiteController::class,
            SiteRoleController::class                 => SiteRoleController::class,
            SitePositionController::class             => SitePositionController::class,
            SiteSlotUsageController::class            => SiteSlotUsageController::class,
            EquipmentController::class                => EquipmentController::class,
            SiteTestingDailyScheduleController::class => SiteTestingDailyScheduleController::class,
            DefaultBrakeTestsController::class        => DefaultBrakeTestsController::class,
            MotTestInProgressController::class        => MotTestInProgressController::class,
            SiteContactController::class              => SiteContactController::class,
            SitePositionValidateController::class => SitePositionValidateController::class,
        ],
        'factories' => [
            SiteSearchControllerFactory::class => SiteSearchControllerFactory::class,
        ],
    ],
    'router'      => [
        'routes' => [
            'site-role'               => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/site/:siteId/person/:personId/role',
                    'constraints' => [
                        'siteId'   => '[0-9]+',
                        'personId' => '[a-zA-Z]?[a-zA-Z0-9\.-_@]+',
                    ],
                    'defaults'    => [
                        'controller' => SiteRoleController::class,
                    ],
                ],
            ],
            'site-position'           => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/site/:siteId/position[/:positionId]',
                    'constraints' => [
                        'siteId'     => '[0-9]+',
                        'positionId' => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => SitePositionController::class,
                    ],
                ],
            ],
            'site-position-validate'           => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/site/:siteId/position-validate',
                    'constraints' => [
                        'siteId'     => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => SitePositionValidateController::class,
                    ],
                ],
            ],
            'site-usage' => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/site/:siteId/slot-usage',
                    'constraints' => [
                        'siteId' => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => SiteSlotUsageController::class,
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'period-data' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/period-data',
                            'defaults' => [
                                'action' => 'period-data',
                            ],
                        ],
                    ],
                ],
            ],
            'site'                    => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/site/:id',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => SiteController::class,
                        'action'     => 'siteById'
                    ],
                ],
            ],
            'vehicle-testing-station' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/vehicle-testing-station[/:id]',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => SiteController::class,
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'site-authorised-classes' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/authorised-classes',
                            'defaults' => [
                                'controller' => VehicleTestingStationAuthorisedClassesController::class,
                            ],
                        ],
                    ],
                    'site'          => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/site/:sitenumber',
                            'constraints' => [
                                'sitenumber' => '[0-9a-zA-Z]+',
                            ],
                            'defaults'    => [
                                'controller' => SiteController::class,
                                'action'     => 'findBySiteNumber'
                            ],
                        ],
                    ],
                    'equipment'     => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/equipment',
                            'defaults'    => [
                                'controller' => EquipmentController::class,
                            ],
                        ]
                    ],
                    'default-brake-tests' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/default-brake-tests',
                            'constraints' => [
                                'id' => '[0-9]+',
                            ],
                            'defaults'    => [
                                'controller' => DefaultBrakeTestsController::class,
                            ],
                        ]
                    ],
                    'opening-hours' => [
                        'type'    => 'segment',
                        'options' => [
                            'route'    => '/opening-hours',
                            'defaults' => [
                                'controller' => SiteTestingDailyScheduleController::class
                            ]
                        ],
                    ],
                    'test-in-progress' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/test-in-progress',
                            'defaults'    => [
                                'controller' => MotTestInProgressController::class,
                            ],
                        ]
                    ],
                    'contact'      => [
                        'type'          => 'Segment',
                        'options'       => [
                            'route'    => '/contact[/:contactId]',
                            'constraints' => [
                                'contactId' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => SiteContactController::class,
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'update' => [
                                'type'    => 'Segment',
                                'options' => [
                                    'route' => '/update',
                                    'defaults' => [
                                        'controller' => SiteContactController::class,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'vehicle-testing-station-search' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/vehicle-testing-station/search',
                    'defaults' => [
                        'controller' => SiteSearchControllerFactory::class,
                    ],
                ],
            ],
        ],
    ],
];
