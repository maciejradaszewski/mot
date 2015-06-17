<?php

namespace DvsaAuthentication\Factory;


use DvsaAuthentication\Authentication\Service\ApiTokenService;
use Zend\Http\Request;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;

class ApiTokenServiceFactory implements FactoryInterface {

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $apiTokenService = new ApiTokenService($serviceLocator->get('Request'));

        return $apiTokenService;
    }
}