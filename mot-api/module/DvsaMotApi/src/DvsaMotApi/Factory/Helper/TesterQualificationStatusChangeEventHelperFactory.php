<?php

namespace DvsaMotApi\Factory\Helper;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Doctrine\ORM\EntityManager;
use DvsaMotApi\Helper\TesterQualificationStatusChangeEventHelper;
use DvsaCommon\Date\DateTimeHolder;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaEventApi\Service\EventService;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\EventPersonMap;

class TesterQualificationStatusChangeEventHelperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get(EntityManager::class);

        return new TesterQualificationStatusChangeEventHelper(
            $serviceLocator->get(MotIdentityProviderInterface::class),
            $serviceLocator->get(EventService::class),
            $entityManager->getRepository(EventPersonMap::class),
            $entityManager->getRepository(Person::class),
            new DateTimeHolder()
        );
    }
}
