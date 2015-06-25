<?php

namespace DvsaMotApi\Factory\Service;

use DvsaEntities\Entity\MotTest;
use DvsaMotApi\Service\TesterMotTestLogService;
use OrganisationApi\Service\Mapper\MotTestLogSummaryMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class TesterMotTestLogServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new TesterMotTestLogService(
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get(\Doctrine\ORM\EntityManager::class)->getRepository(MotTest::class),
            new MotTestLogSummaryMapper()
        );
    }
}
