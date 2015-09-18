<?php

namespace Core\Factory;

use Core\Service\SessionService;
use Core\Service\StepService;
use DvsaCommonTest\TestUtils\XMock;
use Zend\ServiceManager\ServiceManager;

/**
 * Class StepServiceFactoryTest.
 *
 * @group registration
 * @group VM-11506
 */
class StepServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateService()
    {
        $serviceManager = new ServiceManager();

        $session = XMock::of(SessionService::class);

        $serviceManager->setService(SessionService::class, $session);

        $factory = new StepServiceFactory();

        $this->assertInstanceOf(
            StepService::class,
            $factory->createService($serviceManager)
        );
    }
}
