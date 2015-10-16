<?php

namespace PersonApiTest\Factory\Service;

use DvsaCommonTest\TestUtils\XMock;
use PersonApi\Service\PasswordExpiryNotificationService;
use PersonApi\Factory\Service\PasswordExpiryNotificationServiceFactory;
use NotificationApi\Service\NotificationService;
use DvsaEntities\Entity\Notification;
use DvsaEntities\Repository\NotificationRepository;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\PasswordDetail;
use DvsaEntities\Repository\PersonRepository;
use DvsaEntities\Repository\PasswordDetailRepository;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use Doctrine\ORM\EntityManager;

class PasswordExpiryNotificationServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager();

        $entityManager = XMock::of(EntityManager::class);
        $entityManager
            ->expects($this->any())
            ->method("getRepository")
            ->willReturnCallback(function ($entity) {
                switch ($entity) {
                    case Notification::class:
                        return XMock::of(NotificationRepository::class);
                    case Person::class:
                        return XMock::of(PersonRepository::class);
                    case PasswordDetail::class:
                        return XMock::of(PasswordDetailRepository::class);
                    default:
                        return null;
                }
            });

        $serviceManager->setService(NotificationService::class, XMock::of(NotificationService::class));
        $serviceManager->setService(EntityManager::class, $entityManager);

        // Create the factory
        $factory = new PasswordExpiryNotificationServiceFactory();
        $factoryResult = $factory->createService($serviceManager);

        $this->assertInstanceOf(PasswordExpiryNotificationService::class, $factoryResult);
    }
}
