<?php

namespace CensorApiTest\Factory\Service;

use CensorApi\Factory\Service\CensorServiceFactory;
use CensorApi\Service\CensorService;
use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Repository\CensorPhraseRepository;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class CensorServiceFactoryTest
 *
 */
class CensorServiceFactoryTest extends AbstractServiceTestCase
{
    /* @var CensorServiceFactory $censorServiceFactory */
    private $censorServiceFactory;

    private $serviceLocator;
    private $censorPhraseRepositoryMock;

    public function setUp()
    {
        $this->censorServiceFactory = new CensorServiceFactory();
        $this->censorPhraseRepositoryMock = XMock::of(CensorPhraseRepository::class);
        $this->serviceLocator = new ServiceManager();
        $this->serviceLocator->setService('CensorPhraseRepository', $this->censorPhraseRepositoryMock);
    }

    public function testCensorServiceFactoryReturnsInstance()
    {
        $service = $this->censorServiceFactory->createService($this->serviceLocator);
        $this->assertInstanceOf(CensorService::class, $service);
    }
}
