<?php

namespace DvsaMotTest\NewVehicle\Controller\Factory;

use DvsaCommon\HttpRestJson\Client;
use DvsaMotTest\NewVehicle\Container\NewVehicleContainer;
use DvsaMotTest\NewVehicle\Controller\CreateVehicleController;
use DvsaMotTest\Service\AuthorisedClassesService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Session\Container;


class CreateVehicleControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     *
     * @return CreateVehicleController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /* @var ServiceLocatorInterface $sl */
        $sl = $controllerManager->getServiceLocator();
        $authorisedClassesService = $sl->get(AuthorisedClassesService::class);
        $authService = $sl->get('AuthorisationService');
        $catalogService = $sl->get('CatalogService');
        $request = $sl->get('Request');
        $client = $sl->get(Client::class);
        $identityProvider = $sl->get('MotIdentityProvider');

        return new CreateVehicleController(
            $authorisedClassesService,
            $catalogService,
            $identityProvider,
            $authService,
            new NewVehicleContainer(new Container(self::class)),
            $request,
            $client
        );
    }
}
