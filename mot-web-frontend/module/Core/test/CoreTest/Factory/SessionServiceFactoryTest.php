<?php

namespace Core\Service;

use Core\Factory\SessionServiceFactory;
use DvsaClient\MapperFactory;
use DvsaCommonTest\TestUtils\XMock;
use Zend\ServiceManager\ServiceManager;

/**
 * Class SessionServiceFactoryTest.
 *
 * @group VM-11506
 */
class SessionServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @throws \Exception
     */
    public function testCreateService()
    {
        $serviceManager = new ServiceManager();

        $service = XMock::of(MapperFactory::class);

        $serviceManager->setService(MapperFactory::class, $service);

        $factory = new SessionServiceFactory();

        $this->assertInstanceOf(
            SessionService::class,
            $factory->createService($serviceManager)
        );
    }
}
