<?php

namespace DvsaMotApiTest\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommonTest\TestUtils\XMock;
use DvsaMotApi\Factory\Service\ReplacementCertificateUpdaterFactory;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaMotApi\Service\ReplacementCertificate\ReplacementCertificateUpdater;
use DvsaMotApi\Service\MotTestSecurityService;

/**
 * Class ReplacementCertificateUpdaterFactoryTest
 *
 */
class ReplacementCertificateUpdatersFactoryTest extends AbstractServiceTestCase
{
    private $serviceLocator;

    public function setUp()
    {
        $motTestSecurityService = XMock::of(MotTestSecurityService::class);
        $authorisationService = XMock::of(AuthorisationServiceInterface::class);
        $authenticationService = XMock::of(AuthenticationService::class);

        $this->serviceLocator = new ServiceManager();
        $this->serviceLocator->setService('DvsaAuthorisationService', $authorisationService);
        $this->serviceLocator->setService('MotTestSecurityService', $motTestSecurityService);
        $this->serviceLocator->setService('DvsaAuthenticationService', $authenticationService);
    }

    public function testReplacementCertificateServiceFactory()
    {
        $service = (new ReplacementCertificateUpdaterFactory())->createService($this->serviceLocator);

        $this->assertInstanceOf(
            ReplacementCertificateUpdater::class,
            $service
        );
    }
}
