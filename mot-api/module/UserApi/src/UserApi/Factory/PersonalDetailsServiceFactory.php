<?php

namespace UserApi\Factory;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use UserApi\Person\Service\PersonalDetailsService;
use UserApi\Person\Service\Validator\PersonalDetailsValidator;
use DvsaCommonApi\Filter\XssFilter;

/**
 * Factory for PersonalDetailsService
 */
class PersonalDetailsServiceFactory implements FactoryInterface
{
    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     * @return \UserApi\Person\Service\PersonalDetailsService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new PersonalDetailsService(
            $serviceLocator->get(EntityManager::class),
            new PersonalDetailsValidator(),
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get(MotIdentityProviderInterface::class),
            $serviceLocator->get(XssFilter::class)
        );
    }
}
