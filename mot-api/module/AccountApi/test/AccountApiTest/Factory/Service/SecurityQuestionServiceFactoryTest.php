<?php
namespace AccountApiTest\Service\Factory;

use Doctrine\ORM\EntityManager;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\TestCaseTrait;
use DvsaCommonTest\TestUtils\XMock;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use AccountApi\Factory\Service\SecurityQuestionServiceFactory;
use AccountApi\Service\SecurityQuestionService;
use DvsaEntities\Repository\SecurityQuestionRepository;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class SecurityQuestionServiceFactoryTest
 *
 * @package AccountApiTest\Service\Factory
 */
class SecurityQuestionServiceFactoryTest extends AbstractServiceTestCase
{
    use TestCaseTrait;

    public function testEventServiceGetList()
    {
        $serviceManager = new ServiceManager();

        $mockEntityManager = XMock::of(EntityManager::class);
        $serviceManager->setService(EntityManager::class, $mockEntityManager);

        $mockSecurityQuestionRepo = XMock::of(SecurityQuestionRepository::class);
        $this->mockMethod($mockEntityManager, 'getRepository', $this->once(), $mockSecurityQuestionRepo);

        $mockParamObfuscator = XMock::of(ParamObfuscator::class);
        $serviceManager->setService(ParamObfuscator::class, $mockParamObfuscator);

        $factory = new SecurityQuestionServiceFactory();

        $this->assertInstanceOf(
            SecurityQuestionService::class,
            $factory->createService($serviceManager)
        );
    }
}
