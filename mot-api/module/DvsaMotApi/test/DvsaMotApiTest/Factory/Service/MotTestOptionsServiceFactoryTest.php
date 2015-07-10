<?php

namespace DvsaMotApiTest\Factory;

use Doctrine\ORM\EntityManager;
use DvsaCommonApi\Authorisation\Assertion\ReadMotTestAssertion;
use DvsaCommonTest\TestUtils\TestCaseTrait;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Repository\MotTestRepository;
use DvsaMotApi\Factory\Service\MotTestOptionsServiceFactory;
use DvsaMotApi\Service\MotTestOptionsService;
use Zend\ServiceManager\ServiceLocatorInterface;

class MotTestOptionsServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    public function testMotTestOptionsServiceFactory()
    {
        $mockServiceLocator = XMock::of(ServiceLocatorInterface::class, ['get']);
        $entityManager = XMock::of(EntityManager::class);

        $this->mockMethod($entityManager, 'getRepository', $this->at(0), XMock::of(MotTestRepository::class));
        $this->mockMethod($mockServiceLocator, 'get', $this->at(0), $entityManager);
        $this->mockMethod($mockServiceLocator, 'get', $this->at(1), XMock::of(ReadMotTestAssertion::class));

        $this->assertInstanceOf(
            MotTestOptionsService::class,
            (new MotTestOptionsServiceFactory())->createService($mockServiceLocator)
        );
    }
}
