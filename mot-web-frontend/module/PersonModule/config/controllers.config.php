<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

use Dvsa\Mot\Frontend\PersonModule\Controller\PersonProfileController;
use Dvsa\Mot\Frontend\PersonModule\Factory\Controller\PersonProfileControllerFactory;

return [
    'factories' => [
        PersonProfileController::class    => PersonProfileControllerFactory::class,
    ],
];
