<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

use Dvsa\Mot\Frontend\ProfileModule\Controller\PersonProfileController;
use Dvsa\Mot\Frontend\ProfileModule\Factory\Controller\PersonProfileControllerFactory;

return [
    'factories' => [
        PersonProfileController::class    => PersonProfileControllerFactory::class,
    ],
];
