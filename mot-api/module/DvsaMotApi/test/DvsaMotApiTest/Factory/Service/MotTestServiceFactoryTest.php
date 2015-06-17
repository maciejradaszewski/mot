<?php

namespace DvsaMotApiTest\Factory;

use Doctrine\ORM\EntityManager;
use DvsaAuthorisation\Service\AuthorisationService;
use DvsaCommonApi\Authorisation\Assertion\ReadMotTestAssertion;
use DvsaCommonTest\TestUtils\TestCaseTrait;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Repository\ConfigurationRepository;
use DvsaMotApi\Factory\Service\MotTestServiceFactory;
use DvsaMotApi\Service\Mapper\MotTestMapper;
use DvsaMotApi\Service\MotTestService;
use DvsaMotApi\Service\OtpService;
use DvsaMotApi\Service\TesterService;
use DvsaMotApi\Service\Validator\MotTestValidator;
use DvsaMotApi\Service\Validator\RetestEligibility\RetestEligibilityValidator;
use OrganisationApi\Service\OrganisationService;
use VehicleApi\Service\VehicleService;
use Zend\ServiceManager\ServiceLocatorInterface;

class MotTestServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    public function testFactory()
    {
        $mockServiceLocator = XMock::of(ServiceLocatorInterface::class, ['get']);
        $this->mockMethod($mockServiceLocator, 'get', $this->at(0), XMock::of(EntityManager::class));
        $this->mockMethod($mockServiceLocator, 'get', $this->at(1), XMock::of(MotTestValidator::class));
        $this->mockMethod($mockServiceLocator, 'get', $this->at(2), XMock::of(AuthorisationService::class));
        $this->mockMethod($mockServiceLocator, 'get', $this->at(3), XMock::of(TesterService::class));
        $this->mockMethod($mockServiceLocator, 'get', $this->at(4), XMock::of(RetestEligibilityValidator::class));
        $this->mockMethod($mockServiceLocator, 'get', $this->at(5), XMock::of(ConfigurationRepository::class));
        $this->mockMethod($mockServiceLocator, 'get', $this->at(6), XMock::of(MotTestMapper::class));
        $this->mockMethod($mockServiceLocator, 'get', $this->at(7), XMock::of(OtpService::class));
        $this->mockMethod($mockServiceLocator, 'get', $this->at(8), XMock::of(OrganisationService::class));
        $this->mockMethod($mockServiceLocator, 'get', $this->at(9), XMock::of(VehicleService::class));
        $this->mockMethod($mockServiceLocator, 'get', $this->at(10), XMock::of(ReadMotTestAssertion::class));

        $this->assertInstanceOf(
            MotTestService::class,
            (new MotTestServiceFactory())->createService($mockServiceLocator)
        );
    }
}
