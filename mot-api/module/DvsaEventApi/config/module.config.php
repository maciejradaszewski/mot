<?php

use DvsaEventApi\Controller\EventController;

return [
    'controllers'     => [
        'invokables' => [
            EventController::class       => EventController::class,
        ],
    ],
    'router'          => [
        'routes' => [
            'event-list'     => [
                'type'    => 'segment',
                'options' => [
                    'route'       => '/event/list/:type/:id',
                    'constraints' => [
                        'type' => 'ae|site|person',
                        'id' => '[1-9]+[0-9]*',
                    ],
                    'defaults'    => [
                        'controller' => EventController::class,
                    ],
                ],
            ],
            'event'     => [
                'type'    => 'segment',
                'options' => [
                    'route'       => '/event/:id',
                    'constraints' => [
                        'id' => '[1-9]+[0-9]*',
                    ],
                    'defaults'    => [
                        'controller' => EventController::class,
                    ],
                ],
            ],

        ],
    ],
];
