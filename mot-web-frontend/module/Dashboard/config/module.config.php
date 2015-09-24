<?php

use Dashboard\Controller\MyApplicationsController;
use Dashboard\Controller\NotificationController;
use Dashboard\Controller\UserStatsController;
use Dashboard\Factory\Controller\UserHomeControllerFactory;
use Dashboard\Factory\Controller\SecurityQuestionControllerFactory;

return [

    'controllers'  => [
        'invokables' => [
            MyApplicationsController::class => MyApplicationsController::class,
            UserStatsController::class      => UserStatsController::class,
            NotificationController::class   => NotificationController::class
        ],
        'factories'  => [
            UserHomeControllerFactory::class => UserHomeControllerFactory::class,
            SecurityQuestionControllerFactory::class => SecurityQuestionControllerFactory::class,
        ]
    ],
    'router'       => [
        'routes' => [
            'user-home' => [
                'type'          => 'Zend\Mvc\Router\Http\Literal',
                'options'       => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => UserHomeControllerFactory::class,
                        'action'     => 'userHome',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'stats'           => [
                        'type'    => 'segment',
                        'options' => [
                            'route'    => 'stats',
                            'defaults' => [
                                'controller' => UserStatsController::class,
                                'action'     => 'show',
                            ],
                        ],
                    ],
                    'my-applications' => [
                        'type'    => 'segment',
                        'options' => [
                            'route'    => 'my-applications',
                            'defaults' => [
                                'controller' => MyApplicationsController::class,
                                'action'     => 'myApplications',
                            ],
                        ],
                    ],
                    'profile'         => [
                        'type'         => 'segment',
                        'options'      => [
                            'route'    => 'profile',
                            'defaults' => [
                            ],
                        ],
                        //  'may_terminate' => true,
                        'child_routes' => [
                            'byId'              => [
                                'type'          => 'segment',
                                'options'       => [
                                    'route'       => '[/:id]',
                                    'constraints' => [
                                        'action' => '[0-9]+',
                                    ],
                                    'defaults'    => [
                                        'action' => 'profile',
                                    ],
                                ],
                                'may_terminate' => true,

                                'child_routes'  => [
                                    'mot-testing' => [
                                        'type'    => 'segment',
                                        'options' => [
                                            'route'    => '/mot-testing',
                                            'defaults' => [
                                                'action' => 'motTesting',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'edit'              => [
                                'type'          => 'segment',
                                'options'       => [
                                    'route'    => '/edit',
                                    'defaults' => [
                                        'action' => 'edit',
                                    ],
                                ],
                                'may_terminate' => true,
                            ],
                            'secutiry-settings' => [
                                'type'    => 'segment',
                                'options' => [
                                    'route'    => '/security-settings',
                                    'defaults' => [
                                        'action' => 'securitySettings',
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

                        ],
                    ],
                    'notification'    => [
                        'type'         => 'segment',
                        'options'      => [
                            'route'         => 'notification',
                            'may_terminate' => false,
                        ],
                        'child_routes' => [
                            'item'   => [
                                'type'    => 'segment',
                                'options' => [
                                    'route'    => '/:notificationId',
                                    'defaults' => [
                                        'controller' => NotificationController::class,
                                        'action'     => 'notification',
                                    ],
                                ],
                            ],
                            'list'   => [
                                'type'    => 'segment',
                                'options' => [
                                    'route'    => '/list',
                                    'defaults' => [
                                        'controller' => NotificationController::class,
                                        'action'     => 'list',
                                    ],
                                ],
                            ],
                            'action' => [
                                'type'    => 'segment',
                                'options' => [
                                    'route'    => '/action',
                                    'defaults' => [
                                        'controller' => NotificationController::class,
                                        'action'     => 'confirmNomination',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_map'        => [
            'notification/list' => __DIR__ . '/../view/dashboard/partials/notification/list.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
