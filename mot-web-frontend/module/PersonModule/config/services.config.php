<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

use Dvsa\Mot\Frontend\PersonModule\Factory\Security\PersonProfileGuardBuilderFactory;
use Dvsa\Mot\Frontend\PersonModule\Security\PersonProfileGuardBuilder;

return [
    'factories' => [
        PersonProfileGuardBuilder::class => PersonProfileGuardBuilderFactory::class,
    ],
];
