<?php

namespace OrganisationApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use OrganisationApi\Model\NominationVerifier;
use OrganisationApi\Model\Operation\NominateByRequestOperation;
use OrganisationApi\Service\OrganisationNominationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class NominateByRequestOperationFactory
 * @package OrganisationApi\Factory\Service
 */
class NominateByRequestOperationFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new NominateByRequestOperation(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get(NominationVerifier::class),
            $serviceLocator->get(OrganisationNominationService::class)
        );
    }
}
