<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

use Dvsa\Mot\Api\RegistrationModule\Controller\RegistrationController;

return [
    'router' => [
        'routes' => [
            'account-register' => [
                'type'    => 'Segment',
                'options' => [
                    'route'       => '/account/register',
                    'defaults'    => [
                        'controller' => RegistrationController::class,
                    ],
                ],
            ],
            'email-checker' => [
                'type'    => 'Segment',
                'options' => [
                    // for the moment will keep it under the white-listed url
                    'route'       => '/account/register/check-email',
                    'defaults'    => [
                        'controller' => RegistrationController::class,
                        'action'     => 'checkDuplicatedEmail',
                    ],
                ],
            ],
        ],
    ],
];
