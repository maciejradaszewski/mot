<?php

namespace DvsaEventApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaEntities\Entity\Event;
use DvsaEntities\Entity\EventType;
use DvsaEventApi\Service\EventService;
use DvsaEventApi\Service\Mapper\EventListMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class EventServiceFactory
 * @package DvsaEventApi\Factory\Service
 */
class EventServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get(EntityManager::class);
        return new EventService(
            $serviceLocator->get('DvsaAuthorisationService'),
            $entityManager,
            $entityManager->getRepository(Event::class),
            $entityManager->getRepository(EventType::class),
            $serviceLocator->get('Hydrator'),
            new EventListMapper()
        );
    }
}
