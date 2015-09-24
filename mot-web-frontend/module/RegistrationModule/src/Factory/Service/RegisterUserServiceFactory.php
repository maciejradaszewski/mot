<?php

namespace Dvsa\Mot\Frontend\RegistrationModule\Factory\Service;

use Dvsa\Mot\Frontend\RegistrationModule\Service\RegisterUserService;
use DvsaCommon\HttpRestJson\Client as HttpRestJsonClient;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RegisterUserServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return RegisterUserService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $httpRestJsonClient = $serviceLocator->get(HttpRestJsonClient::class);

        return new RegisterUserService(
             $httpRestJsonClient
        );
    }
}
