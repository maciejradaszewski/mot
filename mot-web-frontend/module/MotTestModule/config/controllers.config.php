<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

use Dvsa\Mot\Frontend\MotTestModule\Controller\ContingencyTestController;
use Dvsa\Mot\Frontend\MotTestModule\Factory\Controller\ContingencyTestControllerFactory;

return [
    'factories' => [
        ContingencyTestController::class => ContingencyTestControllerFactory::class,
    ],
];