<?php

namespace PersonApi\Factory\Service;

use PersonApi\Service\PasswordExpiryNotificationService;
use NotificationApi\Service\NotificationService;
use DvsaEntities\Entity\Notification;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\PasswordDetail;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaCommon\Database\Transaction;

class PasswordExpiryNotificationServiceFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $entityManager = $serviceLocator->get(EntityManager::class);

        return new PasswordExpiryNotificationService(
            $serviceLocator->get(NotificationService::class),
            $entityManager->getRepository(Notification::class),
            $entityManager->getRepository(Person::class),
            $entityManager->getRepository(PasswordDetail::class),
            new Transaction($entityManager)
        );
    }
}
