<?php

namespace PersonApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Validator\DateOfBirthValidator;
use PersonApi\Service\PersonDateOfBirthService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PersonDateOfBirthServiceFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return PersonDateOfBirthService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get(EntityManager::class);
        $dayOfBirthValidator = new DateOfBirthValidator();
        $authService = $serviceLocator->get('DvsaAuthorisationService');

        return new PersonDateOfBirthService($entityManager, $dayOfBirthValidator, $authService);
    }
}