<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\RegistrationModuleTest\Factory\Controller;

use Dvsa\Mot\Frontend\RegistrationModule\Service\RegistrationStepService;
use Dvsa\MOT\Frontend\RegistrationModule\Controller\DetailsController;
use Dvsa\Mot\Frontend\RegistrationModule\Factory\Controller\DetailsControllerFactory;
use DvsaCommonTest\TestUtils\XMock;
use Zend\Mvc\Controller\ControllerManager;
use Zend\ServiceManager\ServiceManager;

/**
 * Class DetailsControllerFactoryTest.
 */
class DetailsControllerFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @throws \Exception
     */
    public function testFactory()
    {
        $serviceManager = new ServiceManager();

        $service = XMock::of(RegistrationStepService::class);
        $serviceManager->setService(RegistrationStepService::class, $service);

        $plugins = $this->getMockBuilder(ControllerManager::class)->disableOriginalConstructor()->getMock();
        $plugins->expects($this->any())
            ->method('getServiceLocator')
            ->will($this->returnValue($serviceManager));

        //Create Factory
        $factory = new DetailsControllerFactory();
        $factoryResult = $factory->createService($plugins);
        $this->assertInstanceOf(DetailsController::class, $factoryResult);
    }
}
