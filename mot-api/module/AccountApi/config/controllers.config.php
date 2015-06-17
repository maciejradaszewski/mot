<?php

use AccountApi\Controller\ClaimController;
use AccountApi\Factory\Controller\PasswordResetControllerFactory;
use AccountApi\Factory\Controller\PasswordChangeControllerFactory;
use AccountApi\Factory\Controller\PasswordUpdateControllerFactory;
use AccountApi\Factory\Controller\ValidateUsernameControllerFactory;
use AccountApi\Factory\Controller\SecurityQuestionControllerFactory;


return [
    'invokables' => [
        ClaimController::class => ClaimController::class
    ],
    'factories' => [
        PasswordResetControllerFactory::class => PasswordResetControllerFactory::class,
        PasswordResetControllerFactory::class  => PasswordResetControllerFactory::class,
        PasswordChangeControllerFactory::class => PasswordChangeControllerFactory::class,
        PasswordUpdateControllerFactory::class => PasswordUpdateControllerFactory::class,
        PasswordResetControllerFactory::class => PasswordResetControllerFactory::class,
        ValidateUsernameControllerFactory::class => ValidateUsernameControllerFactory::class,
        SecurityQuestionControllerFactory::class => SecurityQuestionControllerFactory::class,
    ]
];