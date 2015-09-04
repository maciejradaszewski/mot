<?php
namespace Dvsa\Mot\Frontend\RegistrationModuleTest\Factory\Service;

use Dvsa\Mot\Frontend\RegistrationModule\Service\RegistrationSessionService;
use DvsaClient\MapperFactory;
use DvsaCommonTest\TestUtils\XMock;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\Session\Storage\SessionStorage;

/**
 * Class RegistrationSessionServiceTest.
 *
 * @group VM-11506
 */
class RegistrationSessionServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Testing Constructor of a RegistrationSessionService.
     */
    public function testConstructor()
    {
        $container = new Container(RegistrationSessionService::UNIQUE_KEY);

        $service = new RegistrationSessionService($container, XMock::of(MapperFactory::class));

        $this->assertInstanceOf(RegistrationSessionService::class, $service);
    }

    /**
     * @throws \Exception
     */
    public function testDestroy()
    {
        $manager = XMock::of(SessionManager::class);
        $manager->expects($this->once())
            ->method('destroy');

        $container = XMock::of(Container::class);
        $container->expects($this->once())
            ->method('getManager')
            ->willReturn($manager);

        $service = new RegistrationSessionService($container, XMock::of(MapperFactory::class));
        $service->destroy();
    }

    /**
     * @throws \Exception
     */
    public function testClear()
    {
        $storage = XMock::of(SessionStorage::class);
        $storage->expects($this->once())
            ->method('clear')
            ->willReturn($storage);

        $manager = XMock::of(SessionManager::class);
        $manager->expects($this->once())
            ->method('getStorage')
            ->willReturn($storage);

        $container = XMock::of(Container::class);
        $container->expects($this->once())
            ->method('getManager')
            ->willReturn($manager);

        $service = new RegistrationSessionService($container, XMock::of(MapperFactory::class));
        $service->clear();
    }

    /**
     * @throws \Exception
     */
    public function testLoadPositive()
    {
        $container = XMock::of(Container::class);
        $container->expects($this->once())
            ->method('offsetExists')
            ->willReturn(true);

        $container->expects($this->once())
            ->method('offsetGet');

        $service = new RegistrationSessionService($container, XMock::of(MapperFactory::class));
        $service->load('key');
    }

    /**
     * @throws \Exception
     */
    public function testLoadNegative()
    {
        $container = XMock::of(Container::class);
        $container->expects($this->once())
            ->method('offsetExists')
            ->willReturn(false);

        $container->expects($this->never())
            ->method('offsetGet');

        $service = new RegistrationSessionService($container, XMock::of(MapperFactory::class));
        $service->load('key');
    }

    /**
     * @throws \Exception
     */
    public function testSave()
    {
        $container = XMock::of(Container::class);
        $container->expects($this->once())
            ->method('offsetSet')
            ->with('key', 'value');

        $service = new RegistrationSessionService($container, XMock::of(MapperFactory::class));
        $service->save('key', 'value');
    }

    /**
     * Test if method is going to return an object type of array.
     */
    public function testToArray()
    {
        $container = new Container(RegistrationSessionService::UNIQUE_KEY);
        $service = new RegistrationSessionService($container, XMock::of(MapperFactory::class));

        $this->assertInternalType('array', $service->toArray());
    }
}
