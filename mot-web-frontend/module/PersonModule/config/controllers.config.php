<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

use Dvsa\Mot\Frontend\PersonModule\Controller\PersonProfileController;
use Dvsa\Mot\Frontend\PersonModule\Factory\Controller\PersonProfileControllerFactory;
use Dashboard\Controller\UserTradeRolesController;
use Dashboard\Factory\Controller\UserTradeRolesControllerFactory;
use UserAdmin\Factory\Controller\ChangeQualificationStatusControllerFactory;
use UserAdmin\Controller\ChangeQualificationStatusController;
use UserAdmin\Factory\Controller\UserProfileControllerFactory;
use UserAdmin\Controller\UserProfileController;

return [
    'factories' => [
        UserProfileControllerFactory::class => UserProfileController::class,
        PersonProfileController::class    => PersonProfileControllerFactory::class,
        UserTradeRolesController::class   => UserTradeRolesControllerFactory::class,
        ChangeQualificationStatusController::class => ChangeQualificationStatusControllerFactory::class
    ],
];
