<?php

namespace PersonApi\Factory\Controller;

use PersonApi\Controller\PersonController;
use PersonApi\Generator\PersonGenerator;
use PersonApi\Service\PersonService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class PersonControllerFactory
 *
 * Generates the PersonController, injecting dependencies
 */
class PersonControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     *
     * @return PersonController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var ServiceManager $serviceLocator */
        $serviceLocator = $controllerManager->getServiceLocator();
        /** @var PersonService $personService */
        $personService = $serviceLocator->get(PersonService::class);
        /** @var PersonGenerator $personGenerator */
        $personGenerator = $serviceLocator->get(PersonGenerator::class);

        return new PersonController($personService, $personGenerator);
    }
}
