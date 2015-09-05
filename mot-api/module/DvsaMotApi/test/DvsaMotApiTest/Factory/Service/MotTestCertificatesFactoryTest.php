<?php

namespace DvsaMotApiTest\Factory;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\EntityRepositoryGenerator;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Repository\MotTestRecentCertificateRepository;
use DvsaMotApi\Factory\MotTestCertificatesServiceFactory;
use DvsaMotApi\Service\CertificateStorageService;
use DvsaMotApi\Service\MotTestCertificatesService;
use MailerApi\Service\MailerService;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceManager;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use Doctrine\ORM\EntityRepository;


class MotTestCertificatesFactoryTest extends AbstractServiceTestCase
{

    private $serviceLocator;

    public function setUp()
    {
        $this->serviceLocator = new ServiceManager();

        $em =  XMock::of(EntityManager::class);
        $pdfRepo = XMock::of(MotTestRecentCertificateRepository::class);
        $mockStorageService = XMock::of(CertificateStorageService::class);
        $mockMailerService = XMock::of(MailerService::class);

        $em->method('getRepository')->willReturn($pdfRepo);

        $this->serviceLocator->setService(EntityManager::class, $em);
        $this->serviceLocator->setService(CertificateStorageService::class, $mockStorageService);
        $this->serviceLocator->setService(MailerService::class, $mockMailerService);
        $this->serviceLocator->setService('DvsaAuthorisationService', $this->getMockAuthorizationService());
    }

    public function testFactory()
    {
        $service = (new MotTestCertificatesServiceFactory())->createService($this->serviceLocator);

        $this->assertInstanceOf(
            MotTestCertificatesService::class,
            $service
        );
    }
}
