<?php

namespace AuditApi;

use AccountApi\Factory\Service\ClaimServiceFactory;
use AccountApi\Factory\Service\Validator\ClaimValidatorFactory;
use AccountApi\Factory\Service\OpenAmIdentityServiceFactory;
use AccountApi\Factory\Service\TokenServiceFactory;
use AccountApi\Factory\Service\SecurityQuestionServiceFactory;
use AccountApi\Service\ClaimService;
use AccountApi\Service\SecurityQuestionService;
use AccountApi\Service\TokenService;
use AccountApi\Service\Validator\ClaimValidator;
use AccountApi\Service\OpenAmIdentityService;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

/**
 * Class Module
 */
class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig()
    {
        return include __DIR__ . '/config/services.config.php';
    }

}
