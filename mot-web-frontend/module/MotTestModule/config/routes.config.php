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
            ],
        ],
    ],
];