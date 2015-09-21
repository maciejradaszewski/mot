<?php

use Core\Factory\MotIdentityProviderFactory;
use Core\Factory\LazyMotFrontendAuthorisationServiceFactory;
use Core\Authorisation\Assertion\WebPerformMotTestAssertion;
use Core\Factory\WebPerformMotTestAssertionFactory;
use Core\Authorisation\Assertion\WebAcknowledgeSpecialNoticeAssertion;
use Core\Factory\WebAcknowledgeSpecialNoticeAssertionFactory;
use DvsaCommon\Configuration\MotConfig;
use DvsaCommon\Configuration\MotConfigFactory;
use DvsaMotTest\NewVehicle\Form\VehicleWizard\CreateVehicleFormWizard;
use DvsaMotTest\NewVehicle\Form\VehicleWizard\Factory\CreateVehicleFormWizardFactory;

return [
    'factories' => [
        'MotIdentityProvider' => MotIdentityProviderFactory::class,
        'AuthorisationService' => LazyMotFrontendAuthorisationServiceFactory::class,
        WebPerformMotTestAssertion::class => WebPerformMotTestAssertionFactory::class,
        WebAcknowledgeSpecialNoticeAssertion::class => WebAcknowledgeSpecialNoticeAssertionFactory::class,
        CreateVehicleFormWizard::class => CreateVehicleFormWizardFactory::class,
        MotConfig::class => MotConfigFactory::class,
    ]
];