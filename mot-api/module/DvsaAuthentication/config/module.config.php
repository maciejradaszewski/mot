<?php

use DvsaAuthentication\Authentication\Adapter\OpenAM\Factory\OpenAMApiCredentialsBasedAdapterFactory;
use DvsaAuthentication\Authentication\Adapter\OpenAM\Factory\OpenAMApiTokenBasedAdapterFactory;
use DvsaAuthentication\Authentication\Adapter\OpenAM\Factory\OpenAMCachedClientFactory;
use DvsaAuthentication\Authentication\Adapter\OpenAM\OpenAMApiCredentialsBasedAdapter;
use DvsaAuthentication\Authentication\Adapter\OpenAM\OpenAMApiTokenBasedAdapter;
use DvsaAuthentication\Authentication\Adapter\OpenAM\OpenAMCachedClient;
use DvsaAuthentication\Authentication\Listener\AuthenticationListenerFactory;
use DvsaAuthentication\Factory\ApiTokenServiceFactory;
use DvsaCommon\Auth\MotIdentityProviderInterface;

return [
    'service_manager' => [
        'factories' => [
            'DvsaAuthenticationService'             => \DvsaAuthentication\Factory\AuthenticationServiceFactory::class,
            AuthenticationListenerFactory::class    => AuthenticationListenerFactory::class,
            MotIdentityProviderInterface::class     => \DvsaAuthentication\Factory\IdentityProviderFactory::class,
            'tokenService'                          => ApiTokenServiceFactory::class,
            OpenAMApiCredentialsBasedAdapter::class => OpenAMApiCredentialsBasedAdapterFactory::class,
            OpenAMApiTokenBasedAdapter::class       => OpenAMApiTokenBasedAdapterFactory::class,
            OpenAMCachedClient::class               => OpenAMCachedClientFactory::class,
        ],
    ]
];
