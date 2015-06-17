<?php

namespace CensorApiTest\Factory\Service;

use CensorApi\Factory\Service\CensorPhraseRepositoryFactory;
use Doctrine\ORM\EntityManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Repository\CensorPhraseRepository;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class CensorPhraseRepositoryFactoryTest
 *
 */
class CensorPhraseRepositoryFactoryTest extends AbstractServiceTestCase
{
    /* @var CensorPhraseRepositoryFactory $censorPhraseRepositoryFactory */
    private $censorPhraseRepositoryFactory;

    private $serviceLocator;
    private $entityManagerMock;

    public function setUp()
    {
        $this->censorPhraseRepositoryFactory = new CensorPhraseRepositoryFactory();
        $this->entityManagerMock = XMock::of(EntityManager::class);
        $this->serviceLocator = new ServiceManager();
        $this->serviceLocator->setService(EntityManager::class, $this->entityManagerMock);
    }

    public function testCensorPhraseRepositoryFactoryReturnsInstance()
    {
        $service = $this->censorPhraseRepositoryFactory->createService($this->serviceLocator);
        $this->assertInstanceOf(CensorPhraseRepository::class, $service);
    }
}
