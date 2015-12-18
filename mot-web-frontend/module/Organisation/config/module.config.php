<?php

use DvsaCommon\Validator\UsernameValidator;
use Organisation\Controller\AuthorisedExaminerPrincipalController;
use Organisation\Controller\MotTestLogController;
use Organisation\Controller\RoleController;
use Organisation\Controller\SearchController;
use Organisation\Controller\SiteController;
use Organisation\Controller\SlotsUsageController;
use Organisation\Factory\Controller\SiteControllerFactory;
use Organisation\Factory\Controller\AuthorisedExaminerControllerFactory;
use Organisation\Factory\Controller\AuthorisedExaminerStatusControllerFactory;
use SlotPurchase\Service\DirectDebitService;
use SlotPurchase\Service\Factory\DirectDebitServiceFactory;

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
                        'type'    => 'literal',
                        'options' => [
                            'route'    => '/create',
                            'defaults' => [
                                'controller' => AuthorisedExaminerControllerFactory::class,
                                'action'     => 'create',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'confirmation' => [
                                'type'    => 'literal',
                                'options' => [
                                    'route'    => '/confirmation',
                                    'defaults' => [
                                        'controller' => AuthorisedExaminerControllerFactory::class,
                                        'action'     => 'confirmation',
                                    ],
                                ],
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
                    ],
                    'edit-status'                => [
                        'type'    => 'segment',
                        'options' => [
                            'route'       => '/edit-status',
                            'defaults'    => [
                                'controller' => AuthorisedExaminerStatusControllerFactory::class,
                            ],
                        ],
                    ],
                    'edit-status-confirm'                => [
                        'type'    => 'segment',
                        'options' => [
                            'route'       => '/edit-status/confirmation',
                            'defaults'    => [
                                'controller' => AuthorisedExaminerStatusControllerFactory::class,
                                'action' => 'confirmation'
                            ],
                        ],
                    ],
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
                    'site'       => [
                        'type'    => 'segment',
                        'options' => [
                            'route'       => '/site',
                            'defaults'    => [
                                'controller' => SiteController::class,
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'link'    => [
                                'type'    => 'segment',
                                'options' => [
                                    'route'       => '/link',
                                    'defaults'    => [
                                        'action'     => 'link',
                                    ],
                                ],
                            ],
                            'unlink'    => [
                                'type'    => 'segment',
                                'options' => [
                                    'route'       => '/unlink[/:linkId]',
                                    'defaults'    => [
                                        'action'     => 'unlink',
                                    ],
                                ],
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
        ],
        'factories' => [
            SiteController::class                      => SiteControllerFactory::class,
            AuthorisedExaminerControllerFactory::class => AuthorisedExaminerControllerFactory::class,
            AuthorisedExaminerStatusControllerFactory::class => AuthorisedExaminerStatusControllerFactory::class,
        ]
    ],
    'services'      => [
        'factories' => [
            DirectDebitService::class                   => DirectDebitServiceFactory::class,
        ],
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
