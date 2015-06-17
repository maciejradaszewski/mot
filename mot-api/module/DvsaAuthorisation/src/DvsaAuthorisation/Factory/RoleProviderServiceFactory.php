<?php

namespace DvsaAuthorisation\Factory;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\RoleProviderService;
use DvsaEntities\Repository\RbacRepository;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class RoleProviderServiceFactory
 * @package DvsaAuthorisation\Factory
 */
class RoleProviderServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var EntityManager $entityManager */
        $entityManager = $serviceLocator->get(\Doctrine\ORM\EntityManager::class);
        return new RoleProviderService(
            new RbacRepository($entityManager)
        );
    }
}
