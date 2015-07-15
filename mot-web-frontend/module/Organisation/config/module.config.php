<?php

use DvsaCommon\Validator\UsernameValidator;
use Organisation\Controller\AuthorisedExaminerPrincipalController;
use Organisation\Controller\MotTestLogController;
use Organisation\Controller\RoleController;
use Organisation\Controller\SearchController;
use Organisation\Controller\SlotsUsageController;
use Site\Controller\VehicleTestingStationController;
use Organisation\Factory\Controller\AuthorisedExaminerControllerFactory;

return [
    UsernameValidator::class => [
        'options' => [
            'max' => 50, // FIXME: This should match DvsaEntities\Entity\Person\Person::FIELD_USERNAME_LENGTH
        ],
    ],
    'router'         => [
        'routes' => [
            'authorised-examiner' => [
                'type'          => 'segment',
                'options'       => [
                    'route'    => '/authorised-examiner[/:id]',
                    'constraints' => [
                        'id' => '[1-9]+[0-9]*',
                    ],
                    'defaults'    => [
                        'controller' => AuthorisedExaminerControllerFactory::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'search'                        => [
                        'type'    => 'literal',
                        'options' => [
                            'route'    => '/search',
                            'defaults' => [
                                'controller' => SearchController::class,
                            ],
                        ],
                    ],
                    'create'                        => [
                        'type'    => 'segment',
                        'options' => [
                            'route'    => '/create',
                            'defaults' => [
                                'controller' => AuthorisedExaminerControllerFactory::class,
                                'action'     => 'create',
                            ],
                        ],
                    ],
                    'edit'                => [
                        'type'    => 'segment',
                        'options' => [
                            'route'       => '/edit',
                            'defaults'    => [
                                'controller' => AuthorisedExaminerControllerFactory::class,
                                'action'     => 'edit',
                            ],
                        ],
                    ],/*
                    'site-slot-usage'               => [
                        'type'    => 'segment',
                        'options' => [
                            'route'       => '/site/:sid/slots-usage[/page/:page]',
                            'constraints' => [
                                'id'   => '[1-9]+[0-9]*',
                                'page' => '[1-9]+',
                            ],
                            'defaults'    => [
                                'controller' => SlotsUsageController::class,
                                'action'     => 'site',
                                'page'       => 1,
                            ],
                        ],
                    ],
                    'slots'                         => [
                        'type'         => 'segment',
                        'options'      => [
                            'route'       => '/slots',
                        ],
                        'child_routes' => [
                            'usage'    => [
                                'type'    => 'segment',
                                'options' => [
                                    'route'       => '/usage[/page/:page][:extension]',
                                    'constraints' => [
                                        'page'      => '[1-9]+',
                                        'extension' => '.[a-z]{3}',
                                    ],
                                    'defaults'    => [
                                        'controller' => SlotsUsageController::class,
                                        'action'     => 'index',
                                        'page'       => 1,
                                    ],
                                ],
                            ],
                        ],
                    ],*/
                    'mot-test-log'       => [
                        'type'    => 'segment',
                        'options' => [
                            'route'       => '/mot-test-log',
                            'defaults'    => [
                                'controller' => MotTestLogController::class,
                                'action'     => 'index',

                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'download'    => [
                                'type'    => 'segment',
                                'options' => [
                                    'route'       => '/csv',
                                    'defaults'    => [
                                        'controller' => MotTestLogController::class,
                                        'action'     => 'downloadCsv',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'roles'                         => [
                        'type'    => 'segment',
                        'options' => [
                            'route'       => '/roles',
                            'defaults'    => [
                                'controller' => RoleController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'list-user-roles'               => [
                        'type'    => 'segment',
                        'options' => [
                            'route'       => '/:personId/list-roles',
                            'constraints' => [
                                'personId' => '[1-9]+[0-9]*',
                            ],
                            'defaults'    => [
                                'controller' => RoleController::class,
                                'action'     => 'listUserRoles',
                            ],
                        ],
                    ],
                    'confirm-nomination'            => [
                        'type'    => 'segment',
                        'options' => [
                            'route'       => '/:nomineeId/confirm-nomination/:roleId',
                            'constraints' => [
                                'personId' => '[1-9]+[0-9]*',
                                'roleId'   => '[1-9]+[0-9]*',
                            ],
                            'defaults'    => [
                                'controller' => RoleController::class,
                                'action'     => 'confirmNomination',
                            ],
                        ],
                    ],
                    'remove-role'                   => [
                        'type'    => 'segment',
                        'options' => [
                            'route'       => '/remove-role/:roleId',
                            'constraints' => [
                                'roleId' => '[1-9]+[0-9]*',
                            ],
                            'defaults'    => [
                                'controller' => RoleController::class,
                                'action'     => 'remove',
                            ],
                        ],
                    ],
                    'remove-role-confirmation'      => [
                        'type'    => 'segment',
                        'options' => [
                            'route'       => '/:personId/remove-role-confirmation',
                            'constraints' => [
                                'personId' => '[1-9]+[0-9]*',
                            ],
                            'defaults'    => [
                                'controller' => RoleController::class,
                                'action'     => 'removeConfirmation',
                            ],
                        ],
                    ],
                    'principals'                    => [
                        'type'    => 'segment',
                        'options' => [
                            'route'       => '/principals',
                            'defaults'    => [
                                'controller' => AuthorisedExaminerPrincipalController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'remove-principal-confirmation' => [
                        'type'    => 'segment',
                        'options' => [
                            'route'       => '/:principalId/remove-principal-confirmation',
                            'constraints' => [
                                'principalId' => '[1-9]+[0-9]*',
                            ],
                            'defaults'    => [
                                'controller' => AuthorisedExaminerPrincipalController::class,
                                'action'     => 'removeConfirmation',
                            ],
                        ],
                    ],
                    'vehicle-testing-station'       => [
                        'type'    => 'segment',
                        'options' => [
                            'route'       => '/vehicle-testing-station/:vehicleTestingStationId',
                            'constraints' => [
                                'vehicleTestingStationId' => '[1-9]+[0-9]*',
                            ],
                            'defaults'    => [
                                'controller' => VehicleTestingStationController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers'    => [
        'invokables' => [
            SearchController::class                      => SearchController::class,
            SlotsUsageController::class                  => SlotsUsageController::class,
            VehicleTestingStationController::class       => VehicleTestingStationController::class,
        ],
        'factories' => [
            AuthorisedExaminerControllerFactory::class => AuthorisedExaminerControllerFactory::class,
        ]
    ],
    'view_manager'   => [
        'template_map'        => [
            'mot-test-log/formatter/vehicle-model-sub-row' =>
                __DIR__ . '/../view/organisation/mot-test-log/formatter/vehicle-model-sub-row.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
