<?php

use OrganisationApi\Controller\AuthorisedExaminerPrincipalController;
use OrganisationApi\Controller\OrganisationPositionController;
use OrganisationApi\Controller\OrganisationRoleController;
use OrganisationApi\Controller\OrganisationSlotUsageController;
use OrganisationApi\Controller\SiteController;
use OrganisationApi\Factory\Controller\MotTestLogControllerFactory;
use OrganisationApi\Factory\Controller\AuthorisedExaminerControllerFactory;
use OrganisationApi\Factory\Controller\AuthorisedExaminerStatusControllerFactory;

return [
    'controllers' => [
        'invokables' => [
            OrganisationSlotUsageController::class       => OrganisationSlotUsageController::class,
            AuthorisedExaminerPrincipalController::class => AuthorisedExaminerPrincipalController::class,
            OrganisationPositionController::class        => OrganisationPositionController::class,
            OrganisationRoleController::class            => OrganisationRoleController::class,
            SiteController::class                        => SiteController::class,
        ],
        'factories' => [
            MotTestLogControllerFactory::class         => MotTestLogControllerFactory::class,
            AuthorisedExaminerControllerFactory::class => AuthorisedExaminerControllerFactory::class,
            AuthorisedExaminerStatusControllerFactory::class => AuthorisedExaminerStatusControllerFactory::class,
        ],
    ],
    'router'      => [
        'routes' => [
            'slot-purchase'                        => [
                'type'         => 'Literal',
                'options'      => [
                    'route' => '/slot-purchase',
                ],
                'child_routes' => [
                    'dd-slot-increment' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/dd-slot-increment',
                            'defaults' => [
                                'controller' => 'OrganisationApi\Controller\DDSlotIncrement',
                            ],
                        ],
                    ],
                ],
            ],
            'organisation'                         => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/organisation/:organisationId',
                    'constraints' => [
                        'organisationId' => '[0-9]+',
                    ],
                ],
            ],
            'authorised-examiner'        => [
                'type'          => 'Segment',
                'options'       => [
                    'route'       => '/authorised-examiner[/:id]',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => AuthorisedExaminerControllerFactory::class,
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'area-offices' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'       => '/area-offices/list',
                            'defaults'    => [
                                'controller' => AuthorisedExaminerStatusControllerFactory::class,
                                'action' => 'getAreaOffices'
                            ],
                        ],
                    ],
                    'status' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/status',
                            'defaults'    => [
                                'controller' => AuthorisedExaminerStatusControllerFactory::class,
                            ],
                        ],
                    ],
                    'number' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'       => '/number[/:number]',
                            'defaults'    => [
                                'controller' => AuthorisedExaminerControllerFactory::class,
                                'action'     => 'getAuthorisedExaminerByNumber',
                            ],
                        ],
                    ],
                    'mot-test-log' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/mot-test-log',
                            'defaults' => [
                                'controller' => MotTestLogControllerFactory::class,
                                'action'     => 'logData',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'summary' => [
                                'type'    => 'Literal',
                                'options' => [
                                    'route'    => '/summary',
                                    'defaults' => [
                                        'controller' => MotTestLogControllerFactory::class,
                                        'action'     => 'summary',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
            'authorised-examiner-principal'        => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/authorised-examiner/:authorisedExaminerId/authorised-examiner-principal[/:id]',
                    'constraints' => [
                        'authorisedExaminerId' => '[0-9]+',
                        'id'                   => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => AuthorisedExaminerPrincipalController::class,
                    ],
                ],
            ],
            'authorised-examiner-slot'             => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/authorised-examiner/:id/slot',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => 'OrganisationApi\Controller\AuthorisedExaminerSlot',
                    ],
                ],
            ],
            'organisation-position'                => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/organisation/:organisationId/position[/:positionId]',
                    'constraints' => [
                        'organisationId' => '[0-9]+',
                        'positionId'     => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => OrganisationPositionController::class,
                    ],
                ],
            ],
            'organisation-role'                    => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/organisation/:organisationId/person/:personId/role',
                    'constraints' => [
                        'organisationId' => '[0-9]+',
                        'personId'       => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => OrganisationRoleController::class,
                    ],
                ],
            ],
            'organisation-vehicle-testing-station' => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/organisation/:organisationId/site',
                    'constraints' => [
                        'organisationId' => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => SiteController::class,
                    ],
                ],
            ],

            'organisation-usage' => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/organisation/:organisationId/slot-usage',
                    'constraints' => [
                        'organisationId' => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => OrganisationSlotUsageController::class,
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'period-data' => [
                        'type'    => 'Literal',
                        'options' => [
                            'route'    => '/period-data',
                            'defaults' => [
                                'action' => 'period-data',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
];
