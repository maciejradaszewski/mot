<?php

namespace Dvsa\Mot\Frontend\RegistrationModuleTest\Factory\Service;

use Dvsa\Mot\Frontend\RegistrationModule\Factory\Service\RegistrationSessionServiceFactory;
use Dvsa\Mot\Frontend\RegistrationModule\Service\RegistrationSessionService;
use DvsaClient\MapperFactory;
use DvsaCommonTest\TestUtils\XMock;
use Zend\ServiceManager\ServiceManager;

/**
 * Class RegistrationSessionServiceFactoryTest.
 *
 * @group VM-11506
 */
class RegistrationSessionServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @throws \Exception
     */
    public function testCreateService()
    {
        $serviceManager = new ServiceManager();

        $service = XMock::of(MapperFactory::class);

        $serviceManager->setService(MapperFactory::class, $service);

        $factory = new RegistrationSessionServiceFactory();

        $this->assertInstanceOf(
            RegistrationSessionService::class,
            $factory->createService($serviceManager)
        );
    }
}
