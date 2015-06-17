<?php

namespace OrganisationApi\Factory\Service;

use DvsaEntities\Entity\MotTest;
use OrganisationApi\Service\Mapper\MotTestLogSummaryMapper;
use OrganisationApi\Service\MotTestLogService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MotTestLogServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new MotTestLogService(
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get(\Doctrine\ORM\EntityManager::class)->getRepository(MotTest::class),
            new MotTestLogSummaryMapper()
        );
    }
}
