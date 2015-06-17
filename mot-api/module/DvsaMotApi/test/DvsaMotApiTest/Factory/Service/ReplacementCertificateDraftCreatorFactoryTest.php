<?php

namespace DvsaCatalogApiTest\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommonTest\TestUtils\XMock;
use DvsaMotApi\Factory\Service\ReplacementCertificateDraftCreatorFactory;
use DvsaMotApi\Service\MotTestSecurityService;
use DvsaMotApi\Service\ReplacementCertificate\ReplacementCertificateDraftCreator;
use Zend\Authentication\AuthenticationService;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceManager;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;

/**
 * Class ReplacementCertificateDraftCreatorFactoryTest
 *
 */
class ReplacementCertificateDraftCreatorFactoryTest extends AbstractServiceTestCase
{
    private $serviceLocator;

    public function setUp()
    {
        $motTestSecurityService = XMock::of(MotTestSecurityService::class);
        $authorisationService = XMock::of(AuthorisationServiceInterface::class);

        $this->serviceLocator = new ServiceManager();
        $this->serviceLocator->setService('DvsaAuthorisationService', $authorisationService);
        $this->serviceLocator->setService('MotTestSecurityService', $motTestSecurityService);
    }

    public function testReplacementCertificateDraftCreatorFactory()
    {
        $service = (new ReplacementCertificateDraftCreatorFactory())->createService($this->serviceLocator);

        $this->assertInstanceOf(
            ReplacementCertificateDraftCreator::class,
            $service
        );
    }
}
