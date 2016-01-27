<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

use Dashboard\Factory\Controller\SecurityQuestionControllerFactory;
use Dvsa\Mot\Frontend\PersonModule\Controller\PersonProfileController;
use Dvsa\Mot\Frontend\PersonModule\View\ContextProvider;
use UserAdmin\Factory\Controller\EmailAddressControllerFactory;
use UserAdmin\Factory\Controller\DrivingLicenceControllerFactory;
use Dashboard\Controller\UserTradeRolesController;
use Dashboard\Factory\Controller\PasswordControllerFactory;
use UserAdmin\Factory\Controller\UserProfileControllerFactory;
use UserAdmin\Controller\ChangeQualificationStatusController;
use UserAdmin\Factory\Controller\ResetAccountClaimByPostControllerFactory;
use UserAdmin\Factory\Controller\PersonRoleControllerFactory;

return [
    'router' => [
        'routes' => [
            'newProfile' => [
                'type'    => 'segment',
                'options' => [
                    'route'    => '/your-profile[/:id]',
                    'defaults' => [
                        'controller' => PersonProfileController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'newProfileEmail' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/email',
                            'defaults' => [
                                'controller' => EmailAddressControllerFactory::class,
                                'action' => 'index'
                            ],
                        ],
                        'may-terminate' => true,
                    ],
                    'driving-licence-change' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/driving-licence',
                            'defaults' => [
                                'controller' => DrivingLicenceControllerFactory::class,
                                'action' => 'index'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'summary' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/summary',
                                    'defaults' => [
                                        'controller' => DrivingLicenceControllerFactory::class,
                                        'action' => 'summary'
                                    ],
                                ],
                            ],
                            'delete' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/delete',
                                    'defaults' => [
                                        'controller' => DrivingLicenceControllerFactory::class,
                                        'action' => 'delete'
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'trade-roles'              => [
                        'type'          => 'segment',
                        'options'       => [
                            'route'       => '/trade-roles',
                            'defaults'    => [
                                'controller' => UserTradeRolesController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'remove-ae-role' => [
                        'type'    => 'segment',
                        'options' => [
                            'route'    => '/remove-ae-role/:entityId/:positionId',
                            'constraints' => [
                                'entityId'   => '[0-9]+',
                                'positionId' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => UserTradeRolesController::class,
                                'action' => 'removeAeRole'
                            ]
                        ]
                    ],
                    'remove-vts-role' => [
                        'type'    => 'segment',
                        'options' => [
                            'route'    => '/remove-vts-role/:entityId/:positionId',
                            'constraints' => [
                                'entityId'   => '[0-9]+',
                                'positionId' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => UserTradeRolesController::class,
                                'action' => 'removeVtsRole'
                            ]
                        ]
                    ],
                    'change-password'              => [
                        'type'          => 'segment',
                        'options'       => [
                            'route'    => '/change-password',
                            'defaults' => [
                                'controller' => PasswordControllerFactory::class,
                                'action' => 'changePassword'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'confirmation' => [
                                'type'    => 'segment',
                                'options' => [
                                    'route'    => '/confirmation',
                                    'defaults' => [
                                        'action' => 'confirmation',
                                    ],
                                ],
                            ],
                        ],

                    ],
                    'security-questions' => [
                        'type'    => 'segment',
                        'options' => [
                            'route'    => '/security-question[/:questionNumber]',
                            'constraints' => [
                                'questionNumber' => '1|2',
                            ],
                            'defaults' => [
                                'controller' => SecurityQuestionControllerFactory::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'username-recover' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/username-recover',
                            'defaults' => [
                                'controller' => UserProfileControllerFactory::class,
                                'action' => 'usernameRecover'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ok' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/ok',
                                    'defaults' => [
                                        'controller' => UserProfileControllerFactory::class,
                                        'action' => 'usernameRecoverOk'
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'change-qualification-status' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/change-qualification-status/:vehicleClassGroup',
                            'defaults' => [
                                'controller' => ChangeQualificationStatusController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'confirmation' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => '/confirmation',
                                    'defaults' => [
                                        'controller' => ChangeQualificationStatusController::class,
                                        'action' => 'confirmation',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
                    ],
                ]
            ],
            'newProfileUserAdmin' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/user-admin/user/[:id]',
                    'defaults' => [
                        'controller' => PersonProfileController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'userAdminEmail' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/email',
                            'defaults' => [
                                'controller' => EmailAddressControllerFactory::class,
                                'action' => 'index'
                            ],
                        ],
                        'may-terminate' => true,
                    ],
                    'driving-licence-change' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/driving-licence',
                            'defaults' => [
                                'controller' => DrivingLicenceControllerFactory::class,
                                'action' => 'index'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'summary' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/summary',
                                    'defaults' => [
                                        'controller' => DrivingLicenceControllerFactory::class,
                                        'action' => 'summary'
                                    ],
                                ],
                            ],
                            'delete' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/delete',
                                    'defaults' => [
                                        'controller' => DrivingLicenceControllerFactory::class,
                                        'action' => 'delete'
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'trade-roles'              => [
                        'type'          => 'segment',
                        'options'       => [
                            'route'       => '/trade-roles',
                            'defaults'    => [
                                'controller' => UserTradeRolesController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'remove-ae-role' => [
                        'type'    => 'segment',
                        'options' => [
                            'route'    => '/remove-ae-role/:entityId/:positionId',
                            'constraints' => [
                                'entityId'   => '[0-9]+',
                                'positionId' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => UserTradeRolesController::class,
                                'action' => 'removeAeRole'
                            ]
                        ]
                    ],
                    'remove-vts-role' => [
                        'type'    => 'segment',
                        'options' => [
                            'route'    => '/remove-vts-role/:entityId/:positionId',
                            'constraints' => [
                                'entityId'   => '[0-9]+',
                                'positionId' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => UserTradeRolesController::class,
                                'action' => 'removeVtsRole'
                            ]
                        ]
                    ],
                    'manage-user-internal-role' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/manage-internal-role',
                            'defaults' => [
                                'controller' => PersonRoleControllerFactory::class,
                                'action' => 'manageInternalRole',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'add-internal-role' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/add/:personSystemRoleId',
                                    'constraints' => [
                                        'personSystemRoleId' => '[0-9]+',
                                    ],
                                    'defaults' => [
                                        'controller' => PersonRoleControllerFactory::class,
                                        'action' => 'addInternalRole',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'remove-internal-role' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/remove/:personSystemRoleId',
                                    'constraints' => [
                                        'personSystemRoleId' => '[0-9]+',
                                    ],
                                    'defaults' => [
                                        'controller' => PersonRoleControllerFactory::class,
                                        'action' => 'removeInternalRole',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ]
                    ],
                    'change-password'              => [
                        'type'          => 'segment',
                        'options'       => [
                            'route'    => '/change-password',
                            'defaults' => [
                                'controller' => PasswordControllerFactory::class,
                                'action' => 'changePassword'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'confirmation' => [
                                'type'    => 'segment',
                                'options' => [
                                    'route'    => '/confirmation',
                                    'defaults' => [
                                        'action' => 'confirmation',
                                    ],
                                ],
                            ],
                        ],

                    ],
                    'username-recover' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/username-recover',
                            'defaults' => [
                                'controller' => UserProfileControllerFactory::class,
                                'action' => 'usernameRecover'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ok' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/ok',
                                    'defaults' => [
                                        'controller' => UserProfileControllerFactory::class,
                                        'action' => 'usernameRecoverOk'
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'change-qualification-status' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/change-qualification-status/:vehicleClassGroup',
                            'defaults' => [
                                'controller' => ChangeQualificationStatusController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'confirmation' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => '/confirmation',
                                    'defaults' => [
                                        'controller' => ChangeQualificationStatusController::class,
                                        'action' => 'confirmation',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
                    ],
                    'claim-reset' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/claim-reset',
                            'defaults' => [
                                'controller' => UserProfileControllerFactory::class,
                                'action' => 'claimAccount',
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'claim-reset-by-post' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/claim-reset/post',
                            'defaults' => [
                                'controller' => ResetAccountClaimByPostControllerFactory::class,
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'user-security-question' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/security-question/:questionNumber',
                            'constraints' => [
                                'questionNumber' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => SecurityQuestionControllerFactory::class,
                            ],
                        ],
                    ],
                    'password-reset' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/password-reset',
                            'defaults' => [
                                'controller' => UserProfileControllerFactory::class,
                                'action' => 'passwordReset'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ok' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/ok',
                                    'defaults' => [
                                        'controller' => UserProfileControllerFactory::class,
                                        'action' => 'passwordResetOk'
                                    ],
                                ],
                            ],
                            'nok' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/nok',
                                    'defaults' => [
                                        'controller' => UserProfileControllerFactory::class,
                                        'action' => 'passwordResetNok'
                                    ],
                                ],
                            ],
                        ],
                    ],
                ]
            ],
            ContextProvider::VTS_PARENT_ROUTE => [
                'type' => 'segment',
                'options' => [
                    'route' => '/vehicle-testing-station/[:vehicleTestingStationId]/user/[:id]',
                    'defaults' => [
                        'controller' => PersonProfileController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'VTSEmail' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/email',
                            'defaults' => [
                                'controller' => EmailAddressControllerFactory::class,
                                'action' => 'index'
                            ],
                        ],
                        'may-terminate' => true,
                    ],
                    'driving-licence-change' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/driving-licence',
                            'defaults' => [
                                'controller' => DrivingLicenceControllerFactory::class,
                                'action' => 'index'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'summary' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/summary',
                                    'defaults' => [
                                        'controller' => DrivingLicenceControllerFactory::class,
                                        'action' => 'summary'
                                    ],
                                ],
                            ],
                            'delete' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/delete',
                                    'defaults' => [
                                        'controller' => DrivingLicenceControllerFactory::class,
                                        'action' => 'delete'
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'trade-roles'              => [
                        'type'          => 'segment',
                        'options'       => [
                            'route'       => '/trade-roles',
                            'defaults'    => [
                                'controller' => UserTradeRolesController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'remove-ae-role' => [
                        'type'    => 'segment',
                        'options' => [
                            'route'    => '/remove-ae-role/:entityId/:positionId',
                            'constraints' => [
                                'entityId'   => '[0-9]+',
                                'positionId' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => UserTradeRolesController::class,
                                'action' => 'removeAeRole'
                            ]
                        ]
                    ],
                    'remove-vts-role' => [
                        'type'    => 'segment',
                        'options' => [
                            'route'    => '/remove-vts-role/:entityId/:positionId',
                            'constraints' => [
                                'entityId'   => '[0-9]+',
                                'positionId' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => UserTradeRolesController::class,
                                'action' => 'removeVtsRole'
                            ]
                        ]
                    ],
                    'change-password'              => [
                        'type'          => 'segment',
                        'options'       => [
                            'route'    => '/change-password',
                            'defaults' => [
                                'controller' => PasswordControllerFactory::class,
                                'action' => 'changePassword'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'confirmation' => [
                                'type'    => 'segment',
                                'options' => [
                                    'route'    => '/confirmation',
                                    'defaults' => [
                                        'action' => 'confirmation',
                                    ],
                                ],
                            ],
                        ],

                    ],
                    'username-recover' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/username-recover',
                            'defaults' => [
                                'controller' => UserProfileControllerFactory::class,
                                'action' => 'usernameRecover'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ok' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/ok',
                                    'defaults' => [
                                        'controller' => UserProfileControllerFactory::class,
                                        'action' => 'usernameRecoverOk'
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'change-qualification-status' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/change-qualification-status/:vehicleClassGroup',
                            'defaults' => [
                                'controller' => ChangeQualificationStatusController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'confirmation' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => '/confirmation',
                                    'defaults' => [
                                        'controller' => ChangeQualificationStatusController::class,
                                        'action' => 'confirmation',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
                    ],
                ]
            ],
            ContextProvider::AE_PARENT_ROUTE => [
                'type' => 'segment',
                'options' => [
                    'route' => '/authorised-examiner/[:authorisedExaminerId]/user/[:id]',
                    'defaults' => [
                        'controller' => PersonProfileController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'AEEmail' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/email',
                            'defaults' => [
                                'controller' => EmailAddressControllerFactory::class,
                                'action' => 'index'
                            ],
                        ],
                        'may-terminate' => true,
                    ],
                    'driving-licence-change' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/driving-licence',
                            'defaults' => [
                                'controller' => DrivingLicenceControllerFactory::class,
                                'action' => 'index'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'summary' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/summary',
                                    'defaults' => [
                                        'controller' => DrivingLicenceControllerFactory::class,
                                        'action' => 'summary'
                                    ],
                                ],
                            ],
                            'delete' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/delete',
                                    'defaults' => [
                                        'controller' => DrivingLicenceControllerFactory::class,
                                        'action' => 'delete'
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'trade-roles'              => [
                        'type'          => 'segment',
                        'options'       => [
                            'route'       => '/trade-roles',
                            'defaults'    => [
                                'controller' => UserTradeRolesController::class,
                                'action' => 'index',
                            ],
                        ],
                    ],
                    'remove-ae-role' => [
                        'type'    => 'segment',
                        'options' => [
                            'route'    => '/remove-ae-role/:entityId/:positionId',
                            'constraints' => [
                                'entityId'   => '[0-9]+',
                                'positionId' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => UserTradeRolesController::class,
                                'action' => 'removeAeRole'
                            ]
                        ]
                    ],
                    'remove-vts-role' => [
                        'type'    => 'segment',
                        'options' => [
                            'route'    => '/remove-vts-role/:entityId/:positionId',
                            'constraints' => [
                                'entityId'   => '[0-9]+',
                                'positionId' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => UserTradeRolesController::class,
                                'action' => 'removeVtsRole'
                            ]
                        ]
                    ],
                    'change-password'              => [
                        'type'          => 'segment',
                        'options'       => [
                            'route'    => '/change-password',
                            'defaults' => [
                                'controller' => PasswordControllerFactory::class,
                                'action' => 'changePassword'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes'  => [
                            'confirmation' => [
                                'type'    => 'segment',
                                'options' => [
                                    'route'    => '/confirmation',
                                    'defaults' => [
                                        'action' => 'confirmation',
                                    ],
                                ],
                            ],
                        ],

                    ],
                    'username-recover' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/username-recover',
                            'defaults' => [
                                'controller' => UserProfileControllerFactory::class,
                                'action' => 'usernameRecover'
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'ok' => [
                                'type' => 'Segment',
                                'options' => [
                                    'route' => '/ok',
                                    'defaults' => [
                                        'controller' => UserProfileControllerFactory::class,
                                        'action' => 'usernameRecoverOk'
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'change-qualification-status' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/change-qualification-status/:vehicleClassGroup',
                            'defaults' => [
                                'controller' => ChangeQualificationStatusController::class,
                                'action' => 'index',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'confirmation' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => '/confirmation',
                                    'defaults' => [
                                        'controller' => ChangeQualificationStatusController::class,
                                        'action' => 'confirmation',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                        ],
                    ],
                ],
            ]
        ],
    ],
];
