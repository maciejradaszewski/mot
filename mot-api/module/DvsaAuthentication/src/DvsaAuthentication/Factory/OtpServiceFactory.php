<?php

namespace DvsaAuthentication\Factory;

use DvsaAuthentication\Service\OtpService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaEntities\Entity\Person;
use Doctrine\ORM\EntityManager;

class OtpServiceFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return OtpService|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new OtpService(
            $serviceLocator->get('DvsaAuthenticationService'),
            $serviceLocator->get(EntityManager::class)->getRepository(Person::class),
            $serviceLocator->get('ConfigurationRepository')
        );
    }
}
