<?php
namespace AccountTest\Factory\Service;

use DvsaClient\MapperFactory;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommonTest\TestUtils\TestCaseTrait;
use DvsaCommonTest\TestUtils\XMock;
use Account\Factory\Service\SecurityQuestionServiceFactory;
use Account\Service\SecurityQuestionService;
use UserAdmin\Service\UserAdminSessionManager;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class SecurityQuestionServiceFactoryTest
 * @package AccountTest\Factory
 */
class SecurityQuestionServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    public function testEventServiceGetList()
    {
        $mockServiceLocator = XMock::of(ServiceLocatorInterface::class, ['get']);

        $this->mockMethod($mockServiceLocator, 'get', $this->at(0), XMock::of(MapperFactory::class));
        $this->mockMethod($mockServiceLocator, 'get', $this->at(1), XMock::of(UserAdminSessionManager::class));
        $this->mockMethod($mockServiceLocator, 'get', $this->at(2), XMock::of(ParamObfuscator::class));

        $factory = new SecurityQuestionServiceFactory();

        $this->assertInstanceOf(
            SecurityQuestionService::class,
            $factory->createService($mockServiceLocator)
        );
    }
}
