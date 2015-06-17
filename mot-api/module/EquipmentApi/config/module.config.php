<?php

use EquipmentApi\Controller\EquipmentModelController;

return [
    'controllers' => [
        'invokables' => [
            EquipmentModelController::class => EquipmentModelController::class,
        ],
    ],
    'router'      => [
        'routes' => [
            'equipment' => [
                'type'    => 'Segment',
                'options' => [
                    'route'    => '/equipment-model',
                    'defaults' => [
                        'controller' => EquipmentModelController::class,
                    ],
                ],
            ],
        ],
    ],
];
