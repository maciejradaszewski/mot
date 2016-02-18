<?php

namespace PersonApi\Factory\Service;


use Doctrine\ORM\EntityManager;
use DvsaCommon\Validator\AddressValidator;
use PersonApi\Service\PersonAddressService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PersonAddressServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new PersonAddressService(
            $serviceLocator->get(EntityManager::class),
            new AddressValidator(),
            $serviceLocator->get('DvsaAuthorisationService')
        );
    }
}