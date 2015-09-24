<?php

namespace EventTest\Factory\Service;

use DvsaClient\MapperFactory;
use DvsaCommonTest\TestUtils\XMock;
use Event\Factory\Service\EventSessionServiceFactory;
use Event\Service\EventSessionService;
use Zend\ServiceManager\ServiceManager;

/**
 * Class EventSessionServiceFactoryTest.
 *
 * @group event
 */
class EventSessionServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @throws \Exception
     */
    public function testCreateService()
    {
        $serviceManager = new ServiceManager();

        $service = XMock::of(MapperFactory::class);

        $serviceManager->setService(MapperFactory::class, $service);

        $factory = new EventSessionServiceFactory();

        $this->assertInstanceOf(
            EventSessionService::class,
            $factory->createService($serviceManager)
        );
    }
}
