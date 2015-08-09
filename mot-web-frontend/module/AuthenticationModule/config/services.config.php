<?php

use Application\Factory\AuthAdapterFactory;
use Application\Factory\ZendAuthenticationServiceFactory;
use Dvsa\Mot\Frontend\AuthenticationModule\Factory\Listener\WebAuthenticationListenerFactory;
use Dvsa\Mot\Frontend\AuthenticationModule\Factory\OpenAM\OpenAMAuthenticatorFactory;
use Dvsa\Mot\Frontend\AuthenticationModule\Factory\OpenAM\Response\OpenAMAuthFailureBuilderFactory;
use Dvsa\Mot\Frontend\AuthenticationModule\Factory\Service\GotoUrlServiceFactory;
use Dvsa\Mot\Frontend\AuthenticationModule\Factory\Service\IdentitySessionStateServiceFactory;
use Dvsa\Mot\Frontend\AuthenticationModule\Factory\Service\WebAuthenticationCookieServiceFactory;
use Dvsa\Mot\Frontend\AuthenticationModule\Factory\Service\WebLogoutServiceFactory;
use Dvsa\Mot\Frontend\AuthenticationModule\Listener\WebAuthenticationListener;
use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\OpenAMAuthenticator;
use Dvsa\Mot\Frontend\AuthenticationModule\OpenAM\Response\OpenAMAuthFailureBuilder;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\GotoUrlService;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\IdentitySessionStateService;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\WebLogoutService;
use Zend\Authentication\AuthenticationService as ZendAuthenticationService;
use Zend\Cache\Service\StorageCacheAbstractServiceFactory;
use Zend\Log\LoggerAbstractServiceFactory;
use Dvsa\Mot\Frontend\AuthenticationModule\Service\GotoUrlValidatorService;
use \Dvsa\Mot\Frontend\AuthenticationModule\Factory\Service\GotoUrlValidatorServiceFactory;

return [
    'factories' => [
        'AuthAdapter'                      => AuthAdapterFactory::class,
        OpenAMAuthenticator::class         => OpenAMAuthenticatorFactory::class,
        OpenAMAuthFailureBuilder::class    => OpenAMAuthFailureBuilderFactory::class,
        'ZendAuthenticationService'        => ZendAuthenticationServiceFactory::class,
        'tokenService'                     => WebAuthenticationCookieServiceFactory::class,
        WebAuthenticationListener::class   => WebAuthenticationListenerFactory::class,
        WebLogoutService::class            => WebLogoutServiceFactory::class,
        GotoUrlService::class              => GotoUrlServiceFactory::class,
        GotoUrlValidatorService::class     => GotoUrlValidatorServiceFactory::class,
        IdentitySessionStateService::class => IdentitySessionStateServiceFactory::class,
    ],
    'abstract_factories' => [
        StorageCacheAbstractServiceFactory::class,
        LoggerAbstractServiceFactory::class,
    ],
    'aliases' => [
        'translator'                     => 'MvcTranslator',
        ZendAuthenticationService::class => 'ZendAuthenticationService',
    ],
];
