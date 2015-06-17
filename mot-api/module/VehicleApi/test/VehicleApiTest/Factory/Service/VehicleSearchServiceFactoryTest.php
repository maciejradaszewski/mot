<?php

namespace VehicleApiTest\Factory\Service;

use DataCatalogApi\Service\VehicleCatalogService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use DvsaAuthorisation\Service\AuthorisationService;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommonTest\TestUtils\TestCaseTrait;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Repository\DvlaVehicleImportChangesRepository;
use DvsaEntities\Repository\DvlaVehicleRepository;
use DvsaEntities\Repository\MotTestRepository;
use DvsaEntities\Repository\VehicleRepository;
use VehicleApi\Service\VehicleSearchService;
use VehicleApi\Factory\Service\VehicleSearchServiceFactory;
use Zend\ServiceManager\ServiceLocatorInterface;
use DvsaMotApi\Service\TesterService;

class VehicleSearchServiceFactoryTest extends \PHPUnit_Framework_TestCase
{
    use TestCaseTrait;

    public function testFactory()
    {
        $entityManager = XMock::of(EntityManager::class);
        $this->mockMethod($entityManager, 'getRepository', $this->at(0), XMock::of(VehicleRepository::class));
        $this->mockMethod($entityManager, 'getRepository', $this->at(1), XMock::of(DvlaVehicleRepository::class));
        $this->mockMethod($entityManager, 'getRepository', $this->at(2), XMock::of(DvlaVehicleImportChangesRepository::class));
        $this->mockMethod($entityManager, 'getRepository', $this->at(3), XMock::of(MotTestRepository::class));

        $mockServiceLocator = XMock::of(ServiceLocatorInterface::class, ['get']);
        $this->mockMethod($mockServiceLocator, 'get', $this->at(0), $entityManager);
        $this->mockMethod($mockServiceLocator, 'get', $this->at(1), [ 'featureToggle' ] );
        $this->mockMethod($mockServiceLocator, 'get', $this->at(2), XMock::of(AuthorisationService::class));
        $this->mockMethod($mockServiceLocator, 'get', $this->at(3), XMock::of(TesterService::class));
        $this->mockMethod($mockServiceLocator, 'get', $this->at(4), XMock::of(VehicleCatalogService::class));
        $this->mockMethod($mockServiceLocator, 'get', $this->at(5), XMock::of(ParamObfuscator::class));

        $this->assertInstanceOf(
            VehicleSearchService::class,
            (new VehicleSearchServiceFactory())->createService($mockServiceLocator)
        );
    }

    public function testNullConfigReturnFalseForFuzzySearch()
    {
        $config = null;
        $this->assertEquals(false, $this->searchFuzzyEnabledConfigMethod($config));
    }

    public function testFeatureToggleArrayButNoFuzzySearchReturnFalse()
    {
        $config = [
            'feature_toggle' => [
                'none' => 'true'
            ]
        ];

        $this->assertEquals(false, $this->searchFuzzyEnabledConfigMethod($config));
    }

    private function searchFuzzyEnabledConfigMethod($parameter)
    {
        $method = new \ReflectionMethod(VehicleSearchServiceFactory::class, 'getVehicleSearchFuzzyEnabled');
        $method->setAccessible(true);

        return $method->invoke(new VehicleSearchServiceFactory(), $parameter);
    }

    public function testFuzzySearchReturnTrue()
    {
        $config = [
            'feature_toggle' => [
                'vehicleSearchFuzzyEnabled' => true
            ]
        ];

        $this->assertEquals(true, $this->searchFuzzyEnabledConfigMethod($config));
    }


    public function testFuzzySearchReturFalse()
    {
        $config = [
            'feature_toggle' => [
                'vehicleSearchFuzzyEnabled' => false
            ]
        ];

        $this->assertEquals(false, $this->searchFuzzyEnabledConfigMethod($config));
    }

}
