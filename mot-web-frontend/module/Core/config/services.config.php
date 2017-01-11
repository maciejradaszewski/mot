<?php

use Core\Authorisation\Assertion\WebAcknowledgeSpecialNoticeAssertion;
use Core\Authorisation\Assertion\WebPerformMotTestAssertion;
use Core\Catalog\EnumCatalog;
use Core\Factory\EnumCatalogFactory;
use Core\Factory\FlashMessengerFactory;
use Core\Factory\LazyMotFrontendAuthorisationServiceFactory;
use Core\Factory\MotEventManagerFactory;
use Core\Factory\MotIdentityProviderFactory;
use Core\Factory\UrlHelperFactory;
use Core\Factory\HttpRouteMatchFactory;
use Core\Factory\WebPerformMotTestAssertionFactory;
use Core\Factory\WebAcknowledgeSpecialNoticeAssertionFactory;
use Core\Service\MotEventManager;
use Core\Service\MotFrontendIdentityProviderInterface;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommon\Configuration\MotConfig;
use DvsaCommon\Configuration\MotConfigFactory;
use DvsaCommon\Factory\AutoWire\AutoWireFactory;
use DvsaMotTest\NewVehicle\Form\VehicleWizard\CreateVehicleFormWizard;
use DvsaMotTest\NewVehicle\Form\VehicleWizard\Factory\CreateVehicleFormWizardFactory;
use Zend\Mvc\Controller\Plugin\FlashMessenger;
use Zend\View\Helper\Url;
use Zend\Mvc\Router\Http\RouteMatch;

return [
    'factories' => [
        'MotIdentityProvider' => MotIdentityProviderFactory::class,
        MotFrontendIdentityProviderInterface::class => MotIdentityProviderFactory::class,
        MotIdentityProviderInterface::class => MotIdentityProviderFactory::class,
        'AuthorisationService' => LazyMotFrontendAuthorisationServiceFactory::class,
        MotAuthorisationServiceInterface::class => LazyMotFrontendAuthorisationServiceFactory::class,
        WebPerformMotTestAssertion::class => WebPerformMotTestAssertionFactory::class,
        WebAcknowledgeSpecialNoticeAssertion::class => WebAcknowledgeSpecialNoticeAssertionFactory::class,
        CreateVehicleFormWizard::class => CreateVehicleFormWizardFactory::class,
        MotConfig::class => MotConfigFactory::class,
        EnumCatalog::class => EnumCatalogFactory::class,
        Url::class => UrlHelperFactory::class,
        MotEventManager::class => MotEventManagerFactory::class,
        RouteMatch::class => HttpRouteMatchFactory::class,
        FlashMessenger::class => FlashMessengerFactory::class,
    ],
    'abstract_factories' => [
        AutoWireFactory::class,
    ],
];
