<?php

use Core\Catalog\EnumCatalog;
use Core\Factory\DtoReflectiveDeserializerFactory;
use Core\Factory\EnumCatalogFactory;
use Core\Factory\MotIdentityProviderFactory;
use Core\Factory\LazyMotFrontendAuthorisationServiceFactory;
use Core\Authorisation\Assertion\WebPerformMotTestAssertion;
use Core\Factory\WebPerformMotTestAssertionFactory;
use Core\Authorisation\Assertion\WebAcknowledgeSpecialNoticeAssertion;
use Core\Factory\WebAcknowledgeSpecialNoticeAssertionFactory;
use DvsaCommon\Configuration\MotConfig;
use DvsaCommon\Configuration\MotConfigFactory;
use DvsaCommon\DtoSerialization\DtoReflectiveDeserializer;
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
        DtoReflectiveDeserializer::class => DtoReflectiveDeserializerFactory::class,
        EnumCatalog::class => EnumCatalogFactory::class,
    ]
];