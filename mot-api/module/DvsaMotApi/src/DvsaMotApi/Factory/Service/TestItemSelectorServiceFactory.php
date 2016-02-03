<?php

namespace DvsaMotApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaEntities\Repository\TestItemCategoryRepository;
use DvsaMotApi\Service\TestItemSelectorService;
use DvsaCommon\Configuration\MotConfig;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class TestItemSelectorServiceFactory
 *
 * @package DvsaMotApi\Factory\Service
 */
class TestItemSelectorServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return new TestItemSelectorService(
            $serviceLocator->get(EntityManager::class),
            $serviceLocator->get('Hydrator'),
            $serviceLocator->get('RfrRepository'),
            $serviceLocator->get('DvsaAuthorisationService'),
            $serviceLocator->get(TestItemCategoryRepository::CLASS),
            $serviceLocator->get(MotConfig::class)->withDefault([])->get('disabled_rfrs')
        );
    }
}
