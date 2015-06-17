<?php

namespace DvsaAuthorisation\Factory;

use DvsaAuthorisation\Service\AuthorisationService;
use DvsaEntities\Repository\RbacRepository;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class AuthorisationServiceFactory
 *
 * @package DvsaAuthorisation\Factory
 */
class AuthorisationServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new AuthorisationService(
            $serviceLocator->get('DvsaAuthenticationService'),
            new RbacRepository($serviceLocator->get(\Doctrine\ORM\EntityManager::class))
        );
    }
}
