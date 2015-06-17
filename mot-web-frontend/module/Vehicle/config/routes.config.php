<?php

use DvsaMotEnforcement\Controller\MotTestSearchController as EnforcementMotTestSearchController;
use Vehicle\Controller\VehicleController;

return [
    'vehicle' => [
        'type'          => 'segment',
        'options'       => [
            'route'    => '/vehicle',
            'defaults' => [
                'controller' => VehicleController::class,
                'action'     => 'search',
            ],
        ],
        'may_terminate' => true,
        'child_routes'  => [
            'detail' => [
                'type'          => 'segment',
                'options'       => [
                    'route'       => '/:id',
                    'constraints' => [
                        // 'vehicleId' => '[0-9]+',    // legacy mode: vehicleIds are integers
                        'vehicleId' => '[0-9a-zA-Z-_]+', // new mode: vehicleIds are obfuscated
                    ],
                    'defaults'    => [
                        'action' => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes'  => [
                    'history' => [
                        'type'    => 'segment',
                        'options' => [
                            'route'    => '/history',
                            'defaults' => [
                                'controller' => EnforcementMotTestSearchController::class,
                                'action'     => 'motTestSearchByVehicle',
                            ],
                        ],
                    ],
                ],
            ],
            'search' => [
                'type'    => 'literal',
                'options' => [
                    'route'    => '/search',
                    'defaults' => [
                        'action' => 'search',
                    ],
                ],
            ],
            'result' => [
                'type'    => 'literal',
                'options' => [
                    'route'    => '/result',
                    'defaults' => [
                        'action' => 'result',
                    ],
                ],
            ],
        ],
    ],
];
