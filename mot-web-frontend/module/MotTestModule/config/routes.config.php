<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

use Dvsa\Mot\Frontend\MotTestModule\Controller\ContingencyTestController;
use Dvsa\Mot\Frontend\MotTestModule\Controller\SurveyPageController;

return [
    'router' => [
        'routes' => [
            'contingency'                                 => [
                'type'    => 'segment',
                'options' => [
                    'route'    => '/contingency',
                    'defaults' => [
                        'controller' => ContingencyTestController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'contingency-error'                           => [
                'type'    => 'segment',
                'options' => [
                    'route'    => '/contingency-error',
                    'defaults' => [
                        'controller' => ContingencyTestController::class,
                        'action'     => 'error',
                    ],
                ],
            ],
            'survey' => [
                'type' => 'segment',
                'options' => [
                    'route' => '/survey',
                    'defaults' => [
                        'controller' => SurveyPageController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'thanks' => [
                        'type' => 'segment',
                        'options' => [
                            'route' => '/thanks',
                            'defaults' => [
                                'controller' => SurveyPageController::class,
                                'action'     => 'thanks',
                            ],
                        ],
                    ],
                    'reports'                           => [
                        'type'    => 'segment',
                        'options' => [
                            'route'    => '/reports',
                            'defaults' => [
                                'controller' => SurveyPageController::class,
                                'action'     => 'reports',
                            ],
                        ],
                        'may_terminate' => true,
                        'child_routes' => [
                            'downloadCsv' => [
                                'type' => 'segment',
                                'options' => [
                                    'route' => '/download/:month',
                                    'defaults' => [
                                        'controller' => SurveyPageController::class,
                                        'action' => 'downloadReportCsv'
                                    ]
                                ]
                            ]
                        ]
                    ],
                ],
            ],
        ],
    ],
];
