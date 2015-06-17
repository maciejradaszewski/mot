<?php

use UserAdmin\Controller;
use UserAdmin\Factory\Controller\ResetAccountClaimByPostControllerFactory;
use UserAdmin\Factory\Controller\SecurityQuestionControllerFactory;
use UserAdmin\Factory\Controller\UserProfileControllerFactory;

return [
    'controllers' => [
        'invokables' => [
            Controller\UserSearchController::class => Controller\UserSearchController::class,
            Controller\SecurityQuestionController::class => Controller\SecurityQuestionController::class,
        ],
        'factories' => [
            SecurityQuestionControllerFactory::class => SecurityQuestionControllerFactory::class,
            ResetAccountClaimByPostControllerFactory::class => ResetAccountClaimByPostControllerFactory::class,
            UserProfileControllerFactory::class => UserProfileControllerFactory::class,
        ]
    ],
    'view_manager' => [
        'template_path_stack' => [
            'user-admin' => __DIR__ . '/../view',
        ],
    ],
    'router' => [
        'routes' => [
            'user_admin' => [
                'type' => 'Literal',
                'options' => [
                    'route' => '/user-admin',
                    'defaults' => [
                        'action' => 'index'
                    ],
                ],
                'child_routes' => [
                    'user-search' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/search',
                            'defaults' => [
                                'controller' => Controller\UserSearchController::class,
                                'action' => 'index'
                            ],
                        ],
                        'may_terminate' => true,
                    ],
                    'user-search-results' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/results',
                            'defaults' => [
                                'controller' => Controller\UserSearchController::class,
                                'action' => 'results',
                            ],
                        ],
                    ],
                    'user-profile' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/user-profile/:personId',
                            'constraints' => [
                                'personId' => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => UserProfileControllerFactory::class,
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
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
                        ],
                    ],
                ],
            ],
        ],
    ],
];
