<?php

use Organisation\Controller\RoleController;
use Organisation\Factory\Controller\RoleControllerFactory;
use Organisation\Controller\AuthorisedExaminerPrincipalController;
use Organisation\Factory\Controller\AuthorisedExaminerPrincipalControllerFactory;

return [
    'factories' => [
        RoleController::class => RoleControllerFactory::class,
        AuthorisedExaminerPrincipalController::class => AuthorisedExaminerPrincipalControllerFactory::class
    ],
];
