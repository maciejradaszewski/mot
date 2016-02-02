<?php

namespace PersonApi\Factory\Controller;

use PersonApi\Controller\UpdatePersonNameController;
use PersonApi\Service\PersonNameService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

class UpdatePersonNameControllerFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /**
         * @var ServiceManager
         */
        $serviceLocator = $serviceLocator->getServiceLocator();
        /**
         * @var PersonNameService
         */
        $personNameService = $serviceLocator->get(PersonNameService::class);

        return new UpdatePersonNameController($personNameService);
    }
}
