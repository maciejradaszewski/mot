<?php

use Event\Controller\EventController;

return [
    'router'         => [
        'routes' => [
            'event-list'    => [
                'type'    => 'segment',
                'options' => [
                    'route'         => '/event/list/:type/:id',
                    'constraints'   => [
                        'type'  => 'ae|site|person',
                        'id'    => '[1-9]+[0-9]*',
                    ],
                    'defaults'      => [
                        'controller'    => EventController::class,
                        'action'        => 'list',
                    ],
                ],
            ],
            'event-detail'  => [
                'type'    => 'segment',
                'options' => [
                    'route'         => '/event/:type/:id/:event-id',
                    'constraints'   => [
                        'type'      => 'ae|site|person',
                        'id'        => '[1-9]+[0-9]*',
                        'event-id'  => '[1-9]+[0-9]*',
                    ],
                    'defaults'      => [
                        'controller'    => EventController::class,
                        'action'        => 'detail',
                    ],
                ],
            ]
        ],
    ],
    'controllers'    => [
        'invokables' => [
            EventController::class => EventController::class,
        ],
    ],
    'view_manager'   => [
        'template_map'        => [
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
