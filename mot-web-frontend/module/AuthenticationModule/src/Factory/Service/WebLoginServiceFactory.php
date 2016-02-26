<?php

namespace Dvsa\Mot\Frontend\AuthenticationModule\Factory\Service;

use Dvsa\Mot\Frontend\AuthenticationModule\Service\WebLoginService;
use DvsaCommon\DtoSerialization\DtoReflectiveDeserializer;
use DvsaCommon\HttpRestJson\Client;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;


class WebLoginServiceFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var AuthenticationService $authenticationService */
        $authenticationService = $serviceLocator->get('ZendAuthenticationService');
        $deserializer = $serviceLocator->get(DtoReflectiveDeserializer::class);
        $client = $serviceLocator->get(Client::class);
        return new WebLoginService($authenticationService, $client, $deserializer);
    }
}
