<?php

namespace DvsaMotApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaMotApi\Service\MotTestShortSummaryService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MotTestShortSummaryServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new MotTestShortSummaryService(
            $serviceLocator->get(EntityManager::class)
                ->getRepository(\DvsaEntities\Entity\MotTest::class),
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get('MotTestMapper')
        );
    }
}
