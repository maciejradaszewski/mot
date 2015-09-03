<?php

namespace DvsaAuthentication\Factory;

use Doctrine\ORM\EntityManager;
use DvsaAuthentication\Service\OtpService;
use DvsaAuthentication\Service\OtpServiceAdapter\PinOtpServiceAdapter;
use DvsaEntities\Entity\Person;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class OtpServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return OtpService|mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $otpServiceAdapter = new PinOtpServiceAdapter(
            $serviceLocator->get('DvsaAuthenticationService'),
            $serviceLocator->get(EntityManager::class)->getRepository(Person::class),
            $serviceLocator->get('ConfigurationRepository')
        );

        return new OtpService($otpServiceAdapter);
    }
}
