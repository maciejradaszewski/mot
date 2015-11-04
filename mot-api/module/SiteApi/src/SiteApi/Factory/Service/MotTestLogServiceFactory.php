<?php


namespace SiteApi\Factory\Service;

use DvsaEntities\Entity\MotTest;
use SiteApi\Service\Mapper\MotTestLogSummaryMapper;
use SiteApi\Service\MotTestLogService;
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
