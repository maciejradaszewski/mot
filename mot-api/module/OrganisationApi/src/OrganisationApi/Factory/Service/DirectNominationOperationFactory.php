<?php

namespace OrganisationApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use OrganisationApi\Model\NominationVerifier;
use OrganisationApi\Model\Operation\DirectNominationOperation;
use OrganisationApi\Service\OrganisationNominationService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class DirectNominationOperationFactory
 * @package OrganisationApi\Factory\Service
 */
class DirectNominationOperationFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new DirectNominationOperation(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get(NominationVerifier::class),
            $serviceLocator->get(OrganisationNominationService::class)
        );
    }
}
