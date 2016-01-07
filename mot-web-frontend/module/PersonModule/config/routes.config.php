<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

use Dvsa\Mot\Frontend\PersonModule\Controller\PersonProfileController;

return [
    'router' => [
        'routes' => [
            'newProfile' => [
                'type'    => 'segment',
                'options' => [
                    'route'    => '/preview/profile[/:personId]',
                    'defaults' => [
                        'controller' => PersonProfileController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
            ],
        ],
    ],
];
