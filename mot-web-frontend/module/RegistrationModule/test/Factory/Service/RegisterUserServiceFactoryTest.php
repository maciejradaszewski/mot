<?php
/**
 * This file is part of the DVSA MOT Frontend project.
 *
 * @link http://gitlab.clb.npm/mot/mot
 */

namespace Dvsa\Mot\Frontend\RegistrationModuleTest\Factory\Service;

use Dvsa\Mot\Frontend\RegistrationModule\Factory\Service\RegisterUserServiceFactory;
use Dvsa\Mot\Frontend\RegistrationModule\Service\RegisterUserService;
use DvsaCommon\HttpRestJson\Client as HttpRestJsonClient;
use DvsaCommonTest\TestUtils\XMock;
use Zend\ServiceManager\ServiceManager;

class RegisterUserServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testFactory()
    {
        $serviceManager = new ServiceManager();

        $serviceManager->setService(HttpRestJsonClient::class, XMock::of(HttpRestJsonClient::class));

        //Create Factory
        $factory = new RegisterUserServiceFactory();
        $factoryResult = $factory->createService($serviceManager);
        $this->assertInstanceOf(RegisterUserService::class, $factoryResult);
    }
}
