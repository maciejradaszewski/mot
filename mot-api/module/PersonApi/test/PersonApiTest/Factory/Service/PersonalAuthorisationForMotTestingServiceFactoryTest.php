<?php

namespace PersonApiTest\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaCommonTest\TestUtils\TestCaseTrait;
use DvsaCommonTest\TestUtils\XMock;
use NotificationApi\Service\NotificationService;
use PersonApi\Factory\Service\PersonalAuthorisationForMotTestingServiceFactory;
use PersonApi\Service\PersonalAuthorisationForMotTestingService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class PersonalAuthorisationForMotTestingServiceFactoryTest
 *
 * @package PersonApiTest\Factory\Service
 */
class PersonalAuthorisationForMotTestingServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    public function testCreateServiceReturnsService()
    {
        $entityManager = XMock::of(EntityManager::class);

        $mockServiceLocator = XMock::of(ServiceLocatorInterface::class, ['get']);
        $this->mockMethod($mockServiceLocator, 'get', $this->at(0), $entityManager);
        $this->mockMethod($mockServiceLocator, 'get', $this->at(1), XMock::of(NotificationService::class));

        $this->assertInstanceOf(
            PersonalAuthorisationForMotTestingService::class,
            (new PersonalAuthorisationForMotTestingServiceFactory())->createService($mockServiceLocator)
        );
    }
}
