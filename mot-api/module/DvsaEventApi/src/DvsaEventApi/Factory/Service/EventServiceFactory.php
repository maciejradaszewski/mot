<?php
/**
 * This file is part of the DVSA MOT API project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace DvsaEventApi\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaEntities\Entity\Event;
use DvsaEntities\Entity\EventCategory;
use DvsaEntities\Entity\EventOutcome;
use DvsaEntities\Entity\EventType;
use DvsaEntities\Entity\EventTypeOutcomeCategoryMap;
use DvsaEventApi\Service\EventService;
use DvsaEventApi\Service\Mapper\EventListMapper;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * EventService Factory.
 */
class EventServiceFactory implements FactoryInterface
{
    /**
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     *
     * @return \DvsaEventApi\Service\EventService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get(EntityManager::class);

        return new EventService(
            $serviceLocator->get('DvsaAuthorisationService'),
            $entityManager,
            $entityManager->getRepository(Event::class),
            $entityManager->getRepository(EventType::class),
            $entityManager->getRepository(EventCategory::class),
            $entityManager->getRepository(EventOutcome::class),
            $entityManager->getRepository(EventTypeOutcomeCategoryMap::class),
            $serviceLocator->get('Hydrator'),
            new EventListMapper()
        );
    }
}
