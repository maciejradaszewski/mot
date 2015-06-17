<?php

use UserApi\Application\Controller\AccountController;
use UserApi\Application\Controller\ApplicationController;
use UserApi\Dashboard\Controller\DashboardController;
use UserApi\Dashboard\Controller\UserStatsController;
use UserApi\Message\Controller\MessageController;
use UserApi\HelpDesk\Controller\PersonProfileRestrictedController;
use UserApi\HelpDesk\Controller\PersonProfileUnrestrictedController;
use UserApi\HelpDesk\Controller\SearchPersonController;
use UserApi\Person\Controller\AuthorisedExaminerController;
use UserApi\Person\Controller\MotTestingAuthorisationController;
use UserApi\Person\Controller\PersonCurrentMotTestController;
use UserApi\Person\Controller\PersonSiteCountController;
use UserApi\Person\Controller\PersonalDetailsController;
use UserApi\Person\Controller\PersonAuthorisationController;
use UserApi\Person\Controller\PersonByLoginController;
use UserApi\Person\Controller\PersonController;
use UserApi\Person\Controller\ResetPinController;
use UserApi\SpecialNotice\Controller\SpecialNoticeBroadcastController;
use UserApi\SpecialNotice\Controller\SpecialNoticeContentController;
use UserApi\SpecialNotice\Controller\SpecialNoticeController;
use UserApi\Person\Factory\Controller\PersonByLoginControllerFactory;
use UserApi\HelpDesk\Factory\Controller\ResetClaimAccountControllerFactory;

return [
    'controllers' => [
        'invokables' => [
            ApplicationController::class             => ApplicationController::class,
            DashboardController::class               => DashboardController::class,
            UserStatsController::class               => UserStatsController::class,
            PersonController::class                  => PersonController::class,
            MotTestingAuthorisationController::class => MotTestingAuthorisationController::class,
            AccountController::class                 => AccountController::class,
            PersonalDetailsController::class         => PersonalDetailsController::class,
            AuthorisedExaminerController::class      => AuthorisedExaminerController::class,
            SpecialNoticeContentController::class    => SpecialNoticeContentController::class,
            SpecialNoticeBroadcastController::class  => SpecialNoticeBroadcastController::class,
            SpecialNoticeController::class           => SpecialNoticeController::class,
            PersonAuthorisationController::class     => PersonAuthorisationController::class,
            PersonCurrentMotTestController::class    => PersonCurrentMotTestController::class,
            SearchPersonController::class            => SearchPersonController::class,
            PersonProfileRestrictedController::class => PersonProfileRestrictedController::class,
            PersonProfileUnrestrictedController::class => PersonProfileUnrestrictedController::class,
            MessageController::class                 => MessageController::class,
            ResetPinController::class                => ResetPinController::class,
            PersonSiteCountController::class         => PersonSiteCountController::class,
        ],
        'factories' => [
            PersonByLoginController::class              => PersonByLoginControllerFactory::class,
            ResetClaimAccountControllerFactory::class   => ResetClaimAccountControllerFactory::class,
        ]
    ],
    'router'      => [
        'routes' => [
            'applications-for-user'    => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/user/:userId/application',
                    'defaults'    => [
                        'controller' => ApplicationController::class,
                    ],
                    'constraints' => [
                        'userId' => '[0-9]+'
                    ],
                ],
            ],
            'search-person'            => [
                'type'          => 'segment',
                'options'       => [
                    'route'       => '/search-person',
                    'defaults'    => [
                        'controller' => SearchPersonController::class,
                    ],
                    'constraints' => [
                        'id' => '[0-9]+'
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => []
            ],
            'person'                   => [
                'type'          => 'segment',
                'options'       => [
                    'route'       => '/person[/:id]',
                    'defaults'    => [
                        'controller' => PersonController::class,
                    ],
                    'constraints' => [
                        'id' => '[0-9]+'
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'help-desk-reset-claim-account'            => [
                        'type'          => 'segment',
                        'options'       => [
                            'route'       => '/reset-claim-account',
                            'defaults'    => [
                                'controller' => ResetClaimAccountControllerFactory::class,
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'by-login'            => [
                        'type'          => 'segment',
                        'options'       => [
                            'route'       => '/username/:login',
                            'defaults'    => [
                                'controller' => PersonByLoginController::class,
                            ],
                            'constraints' => [
                                'login' => '[a-zA-Z]?[a-zA-Z0-9\.\-_@]*'
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'help-desk-profile-restricted'            => [
                        'type'          => 'segment',
                        'options'       => [
                            'route'       => '/help-desk-profile-restricted',
                            'defaults'    => [
                                'controller' => PersonProfileRestrictedController::class,
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'help-desk-profile-unrestricted'            => [
                        'type'          => 'segment',
                        'options'       => [
                            'route'       => '/help-desk-profile-unrestricted',
                            'defaults'    => [
                                'controller' => PersonProfileUnrestrictedController::class,
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'rbac-roles'          => [
                        'type'          => 'segment',
                        'options'       => [
                            'route'    => '/rbac-roles',
                            'defaults' => [
                                'controller' => PersonAuthorisationController::class,
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'dashboard'           => [
                        'type'          => 'segment',
                        'options'       => [
                            'route'    => '/dashboard',
                            'defaults' => [
                                'controller' => DashboardController::class,
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'current-mot-test'    => [
                        'type'          => 'segment',
                        'options'       => [
                            'route'    => '/current-mot-test',
                            'defaults' => [
                                'controller' => PersonCurrentMotTestController::class,
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'site-count'    => [
                        'type'          => 'segment',
                        'options'       => [
                            'route'    => '/site-count',
                            'defaults' => [
                                'controller' => PersonSiteCountController::class,
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'mot-testing'         => [
                        'type'          => 'segment',
                        'options'       => [
                            'route'    => '/mot-testing',
                            'defaults' => [
                                'controller' => MotTestingAuthorisationController::class,
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'stats'               => [
                        'type'          => 'segment',
                        'options'       => [
                            'route'    => '/stats',
                            'defaults' => [
                                'controller' => UserStatsController::class,
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'authorised-examiner' => [
                        'type'    => 'segment',
                        'options' => [
                            'route'    => '/authorised-examiner',
                            'defaults' => [
                                'controller' => AuthorisedExaminerController::class
                            ]
                        ]
                    ],
                    'special-notice'      => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/special-notice[/:snId]',
                            'defaults' => [
                                'controller' => SpecialNoticeController::class
                            ]
                        ]
                    ],
                    'reset-pin' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/reset-pin',
                            'defaults' => [
                                'controller' => ResetPinController::class,
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                ],
            ],
            'user-account'             => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/user-account[/:id]',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => AccountController::class,
                    ],
                ],
            ],
            'personal-details'         => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/personal-details/:id',
                    'constraints' => [
                        'id' => '[0-9]+',
                    ],
                    'defaults'    => [
                        'controller' => PersonalDetailsController::class,
                    ],
                ]
            ],
            'special-notice-content'   => [
                'type'          => 'segment',
                'options'       => [
                    'route'       => '/special-notice-content[/:id]',
                    'constraints' => [
                        'id' => '[0-9]+'
                    ],
                    'defaults'    => [
                        'controller' => SpecialNoticeContentController::class
                    ]
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'publish' => [
                        'type'    => 'Segment',
                        'options' => [
                            'route'    => '/publish',
                            'defaults' => [
                                'controller' => SpecialNoticeContentController::class,
                                'action'     => "publish"
                            ],
                        ],
                    ],
                ],
            ],
            'special-notice-broadcast' => [
                'type'    => 'Literal',
                'options' => [
                    'route'    => '/special-notice-broadcast',
                    'defaults' => [
                        'controller' => SpecialNoticeBroadcastController::class,
                    ],
                ],
            ],
            'message'    => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/message',
                    'defaults' => [
                        'controller' => MessageController::class
                    ]
                ]
            ]
        ],
    ],
];
