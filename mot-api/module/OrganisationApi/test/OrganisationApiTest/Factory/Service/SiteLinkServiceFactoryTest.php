<?php

namespace OrganisationApiTest\Factory\Service;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\MotIdentity;
use DvsaCommon\Auth\MotIdentityProviderInterface;
use DvsaCommonTest\TestUtils\TestCaseTrait;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Repository\OrganisationRepository;
use DvsaEntities\Repository\OrganisationSiteMapRepository;
use DvsaEntities\Repository\OrganisationSiteStatusRepository;
use DvsaEntities\Repository\SiteRepository;
use DvsaEventApi\Service\EventService;
use NotificationApi\Service\NotificationService;
use OrganisationApi\Factory\Service\SiteLinkServiceFactory;
use OrganisationApi\Service\SiteLinkService;
use SiteApi\Service\MotTestInProgressService;
use Zend\ServiceManager\ServiceManager;

class SiteLinkServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    public function testFactory()
    {
        $serviceManager = new ServiceManager();

        $entityManager = XMock::of(EntityManager::class);

        $serviceManager->setService(EntityManager::class, $entityManager);
        $serviceManager->setService('DvsaAuthorisationService', XMock::of(AuthorisationServiceInterface::class));

        $identityProvider = XMock::of(MotIdentityProviderInterface::class);
        $this->mockMethod($identityProvider, 'getIdentity', null, new MotIdentity(1, 'unitTest'));
        $serviceManager->setService(MotIdentityProviderInterface::class, $identityProvider);

        $serviceManager->setService(EventService::class, XMock::of(EventService::class));
        $serviceManager->setService(NotificationService::class, XMock::of(NotificationService::class));
        $serviceManager->setService(MotTestInProgressService::class, XMock::of(MotTestInProgressService::class));

        $repos = [
            OrganisationRepository::class,
            SiteRepository::class,
            OrganisationSiteMapRepository::class,
            OrganisationSiteStatusRepository::class,
        ];
        foreach ($repos as $idx => $repo) {
            $this->mockMethod($entityManager, 'getRepository', $this->at($idx), XMock::of($repo));
        }

        // Create the factory
        $factory = new SiteLinkServiceFactory();

        $this->assertInstanceOf(SiteLinkService::class, $factory->createService($serviceManager));
    }
}
