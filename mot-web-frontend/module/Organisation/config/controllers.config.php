<?php

use Organisation\Controller\AuthorisedExaminerPrincipalController;
use Organisation\Controller\MotTestLogController;
use Organisation\Controller\RoleController;
use Organisation\Factory\Controller\AuthorisedExaminerPrincipalControllerFactory;
use Organisation\Factory\Controller\MotTestLogControllerFactory;
use Organisation\Factory\Controller\RoleControllerFactory;

return [
    'factories' => [
        RoleController::class                        => RoleControllerFactory::class,
        AuthorisedExaminerPrincipalController::class => AuthorisedExaminerPrincipalControllerFactory::class,
        MotTestLogController::class                  => MotTestLogControllerFactory::class
    ],
];
