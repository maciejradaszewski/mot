<?php

namespace VehicleApiTest\Service;

use DataCatalogApi\Service\VehicleCatalogService;
use Doctrine\Common\Collections\ArrayCollection;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\DqlBuilder\SearchParam\VehicleSearchParam;
use DvsaEntities\Entity\BodyType;
use DvsaEntities\Entity\Colour;
use DvsaEntities\Entity\DvlaMakeModelMap;
use DvsaEntities\Entity\Make;
use DvsaEntities\Entity\Model;
use DvsaEntities\Entity\MotTest;
use DvsaEntities\Entity\Vehicle;
use DvsaEntities\Entity\DvlaVehicle;
use DvsaEntities\Repository\DvlaVehicleImportChangesRepository;
use DvsaEntities\Repository\DvlaVehicleRepository;
use DvsaEntities\Repository\VehicleRepository;
use DvsaEntities\Repository\MotTestRepository;
use Doctrine\ORM\EntityRepository;
use PHPUnit_Framework_MockObject_MockObject as MockObj;
use VehicleApi\Service\VehicleSearchService;
use Zend\Http\Header\Date;
use DvsaMotApi\Service\TesterService;
use DvsaMotApiTest\Factory\VehicleObjectsFactory as VOF;

/**
 * it test functionality of class VehicleSearchService
 *
 * @package VehicleApiTest\Service
 */
class VehicleSearchServiceTest extends AbstractServiceTestCase
{

    /** @var VehicleRepository|MockObj */
    private $mockVehicleRepository;
    /** @var DvlaVehicleRepository|MockObj */
    private $mockDvlaVehicleRepository;
    /** @var MotTestRepository|MockObj */
    private $mockMotTestRepository;
    /** @var MotAuthorisationServiceInterface|MockObj */
    private $mockAuthService;
    /** @var DvlaVehicleImportChangesRepository|MockObj */
    private $mockDvlaVehicleImportChangesRepository;
    /** @var TesterService */
    private $mockTesterService;
    /** @var VehicleCatalogService|MockObj */
    private $mockVehicleCatalog;
    /** @var ParamObfuscator */
    private $paramObfuscator;
    /** @var bool $vehicleSearchFuzzyEnabled */
    private $vehicleSearchFuzzyEnabled = true;

    public function setUp()
    {
        $this->mockVehicleRepository = XMock::of(VehicleRepository::class);
        $this->mockDvlaVehicleRepository = XMock::of(DvlaVehicleRepository::class);
        $this->mockAuthService = XMock::of(AuthorisationServiceInterface::class, ['isGranted', 'assertGranted']);
        $this->mockDvlaVehicleImportChangesRepository = XMock::of(DvlaVehicleImportChangesRepository::class);
        $this->mockMotTestRepository = XMock::of(MotTestRepository::class);
        $this->mockTesterService = XMock::of(TesterService::class);
        $this->mockVehicleCatalog = XMock::of(VehicleCatalogService::class);
        $this->paramObfuscator = XMock::of(ParamObfuscator::class);
    }

    public function testSearchAllParametersNullReturnsEmptyArray()
    {
        $service = $this->getMockService();
        $this->assertEquals([[], false], $service->search());
    }

    public function testSearchReturnsMoreThanOneResultWillReturnSameAsArray()
    {
        $this->getMockVehicleRepositoryWithResult('searchVehicle', $this->getVehicleMockObjects());

        $service = $this->getMockService();
        $result  = $service->search('DUMMY', null, true, true, 10);
        $result  = current($result);

        $this->assertEquals(count($this->getVehicleMockObjects()), count($result));
    }

    public function testSearchNoResultsInVehicleRepoButReturnResultFromDvlaRepo()
    {
        $this->getMockVehicleRepositoryWithResult('searchVehicle', false);
        $this->getMockDvlaVehicleRepositoryWithResult('search', $this->getDvlaVehicleMockObjects());

        $service = $this->getMockService();
        $result  = $service->search('DUMMY', null, true, true, 10);
        $result  = current($result);

        $this->assertEquals(count($this->getDvlaVehicleMockObjects()), count($result));
    }

    public function testSearchReturnsOneResultAndMatchesValuesInExtractVehiclesArray()
    {
        $vehicleObject = VOF::vehicle(1);

        $this->getMockVehicleRepositoryWithResult('searchVehicle', [ $vehicleObject ]);

        $service = $this->getMockService();
        $result  = $service->search('DUMMY', null, true, true, 10);

        $result = current($result);
        $this->assertEquals(1, count($result));
        $result = current($result);

        $this->assertionBetweenVehicleObjectAndVehicleResultArray($vehicleObject, $result);
    }

    public function testSearchResultsMoreThanOneResultAndMatchesValuesInExtractVehiclesArray()
    {
        $vehicleObjects = $this->getVehicleMockObjects();

        $this->getMockVehicleRepositoryWithResult('searchVehicle', $vehicleObjects);

        $service = $this->getMockService();
        $result  = $service->search('DUMMY', null, true, true, 10);
        $result  = current($result);

        $this->assertEquals(count($vehicleObjects), count($result));

        foreach ($result as $key => $vehicleArray) {
            $this->assertionBetweenVehicleObjectAndVehicleResultArray($vehicleObjects[$key], $vehicleArray);
        }
    }

    public function testSearchVehicleDataWithMotDataAllParametersNullReturnsEmptyArray()
    {
        $service = $this->getMockService();
        $this->assertEquals([], $service->searchVehicleWithMotData());
    }

    public function testSearchVehicleDataWithMotDataReturnsMoreThanOneResultWillReturnSameAsArray()
    {
        $this->getMockVehicleRepositoryWithResult('searchVehicle', $this->getVehicleMockObjects());

        $service = $this->getMockService();
        $result  = $service->searchVehicleWithMotData('DUMMY', null, true, 10);

        $this->assertEquals(count($this->getVehicleMockObjects()), count($result));
    }

    public function testSearchVehicleDataWithMotDataNoResultsInVehicleRepoButReturnResultFromDvlaRepo()
    {
        $this->getMockVehicleRepositoryWithResult('searchVehicle', false);
        $this->getMockDvlaVehicleRepositoryWithResult('search', $this->getDvlaVehicleMockObjects());

        $service = $this->getMockService();
        $result  =  $service->searchVehicleWithMotData('DUMMY', null, true, 10);

        $this->assertEquals(count($this->getDvlaVehicleMockObjects()), count($result));
    }

    public function testSearchVehicleDataWithMotDataReturnsOneResultAndMatchesValuesInExtractVehiclesArray()
    {
        $vehicleObject = VOF::vehicle(1);

        $this->getMockVehicleRepositoryWithResult('searchVehicle', [ $vehicleObject ]);

        $service = $this->getMockService();
        $result =  $service->searchVehicleWithMotData('DUMMY', null, true, 10);

        $this->assertEquals(1, count($result));

        $result = current($result);

        $this->assertionBetweenVehicleObjectAndVehicleResultArray($vehicleObject, $result);
    }

    public function testSearchVehicleDataWithMotDataResultsMoreThanOneResultAndMatchesCountOFExtractVehiclesArray()
    {
        $vehicleObjects = $this->getVehicleMockObjects();

        $motTestMock = (new MotTest())->setId(1)->setNumber('100001')->setIssuedDate(new \DateTime());

        $this->getMockVehicleRepositoryWithResult('searchVehicle', $vehicleObjects);
        $this->getMockMotTestRepositoryWithResult('findHistoricalTestsForVehicle', $motTestMock);

        $service = $this->getMockService();
        $result =  $service->searchVehicleWithMotData('DUMMY', null, true, 10);

        $this->assertEquals(count($vehicleObjects), count($result));
    }

    /**
     * @param $vehicleObject Vehicle
     * @param $result array
     */
    private function assertionBetweenVehicleObjectAndVehicleResultArray($vehicleObject, $result)
    {
        $emptyVrmReason = $vehicleObject->getEmptyVrmReason() ? $vehicleObject->getEmptyVrmReason()->getCode() : null;
        $emptyVinReason = $vehicleObject->getEmptyVinReason() ? $vehicleObject->getEmptyVinReason()->getCode() : null;

        $this->assertEquals($result['id'], $vehicleObject->getId());
        $this->assertEquals($result['registration'], $vehicleObject->getRegistration());
        $this->assertEquals($result['emptyRegistrationReason'], $emptyVrmReason);
        $this->assertEquals($result['vin'], $vehicleObject->getVin());
        $this->assertEquals($result['emptyVinReason'], $emptyVinReason);
        $this->assertEquals($result['year'], $vehicleObject->getYear());
        $this->assertEquals($result['firstUsedDate'], DateTimeApiFormat::date($vehicleObject->getFirstUsedDate()));
        $this->assertEquals($result['cylinderCapacity'], $vehicleObject->getCylinderCapacity());
        $this->assertEquals($result['make'], $vehicleObject->getMakeName());
        $this->assertEquals($result['model'], $vehicleObject->getModelName());

        $modelDetail = $vehicleObject->getModelDetail() ? $vehicleObject->getModelDetail()->getName() : null;
        $this->assertEquals($result['modelDetail'], $modelDetail);

        $vehicleClass = $vehicleObject->getVehicleClass() ? $vehicleObject->getVehicleClass()->getCode() : null;
        $this->assertEquals($result['vehicleClass'], $vehicleClass);

        $colour = $vehicleObject->getColour() ? [
            'id' => $vehicleObject->getColour()->getId(),
            'name' => $vehicleObject->getColour()->getName()
        ] : null;

        $this->assertEquals($result['primaryColour'], $colour);

        $secondaryColour = $vehicleObject->getSecondaryColour() ? [
            'id' => $vehicleObject->getSecondaryColour()->getId(),
            'name' => $vehicleObject->getSecondaryColour()->getName()
        ] : null;

        $this->assertEquals($result['secondaryColour'], $secondaryColour);

        $fuelType = $vehicleObject->getFuelType() ? [
            'id'   => $vehicleObject->getFuelType()->getId(),
            'name' => $vehicleObject->getFuelType()->getName()
        ] : null;

        $this->assertEquals($result['fuelType'], $fuelType);

        $bodyType = $vehicleObject->getBodyType() ? $vehicleObject->getBodyType()->getName() : null;
        $this->assertEquals($result['bodyType'], $bodyType);

        $transmissionType = $vehicleObject->getTransmissionType() ? $vehicleObject->getTransmissionType()->getName() : null;
        $this->assertEquals($result['transmissionType'], $transmissionType);

        $this->assertEquals($result['weight'], $vehicleObject->getWeight());
        $this->assertEquals($result['creationDate'], DateTimeApiFormat::date($vehicleObject->getCreatedOn()));
    }

    public function testSearchReturnsOneResultAndMatchesValuesInExtractDvlaVehiclesArray()
    {
        $vehicleObject = VOF::dvlavehicle(1);

        $this->getMockVehicleRepositoryWithResult('searchVehicle', false);
        $this->getMockDvlaVehicleRepositoryWithResult('search', [ $vehicleObject ]);

        $service = $this->getMockService();
        $result =  $service->search('DUMMY', null, true, true, 10);

        $result = current($result);

        $this->assertEquals(1, count($result));

        $result = current($result);

        $this->assertionBetweenDvlaVehicleObjectAndDvlaVehicleResultArray($vehicleObject, $result);
    }

    public function testSearchResultsMoreThanOneResultAndMatchesValuesInExtractDvlaVehiclesArray()
    {
        $vehicleObjects = $this->getDvlaVehicleMockObjects();

        $this->getMockVehicleRepositoryWithResult('searchVehicle', false);
        $this->getMockDvlaVehicleRepositoryWithResult('search', $vehicleObjects);

        $service = $this->getMockService();
        $result  = $service->search('DUMMY', null, true, true, 10);
        $result  = current($result);
        $this->assertEquals(count($vehicleObjects), count($result));

        foreach ($result as $key => $vehicleArray) {
            $this->assertionBetweenDvlaVehicleObjectAndDvlaVehicleResultArray($vehicleObjects[$key], $vehicleArray);
        }
    }

    public function testSearchWithAdditionalDataBadRequestExceptionThrownIfNoParametersGiven()
    {
        $this->setExpectedException('DvsaCommonApi\Service\Exception\BadRequestException');

        $searchParam = new VehicleSearchParam('', 'vin');

        $service = $this->getMockService();
        $service->searchVehicleWithAdditionalData($searchParam);
    }

    public function testSearchWithAdditionalDataWtihSpacesInParametersReturnsSanitizedData()
    {
        $searchParam = new VehicleSearchParam('F N Z 6  1  00', 'vin');

        $this->getMockVehicleRepositoryWithResult('searchVehicle', false);

        $service = $this->getMockService();
        $vehicles = $service->searchVehicleWithAdditionalData($searchParam);

        $this->assertEquals('FNZ6100', $vehicles['searched']['vin']);

        $searchParam = new VehicleSearchParam('F N Z 6  1  00', 'registration');

        $this->getMockVehicleRepositoryWithResult('searchVehicle', false);

        $service = $this->getMockService();
        $vehicles = $service->searchVehicleWithAdditionalData($searchParam);

        $this->assertEquals('FNZ6100', $vehicles['searched']['registration']);
    }

    public function testSearchWithAdditionalDataWithResultsWithVin()
    {
        // 4 Results
        $this->getMockVehicleRepositoryWithResult('search', $this->getVehicleMockObjects());

        $searchParam = new VehicleSearchParam('FNZ6110', 'vin');

        $service = $this->getMockService();
        $vehicles = $service->searchVehicleWithAdditionalData($searchParam);

        $this->assertEquals(4, $vehicles['resultCount']);
        $this->assertEquals(4, $vehicles['totalResultCount']);
        $this->assertEquals(4, count($vehicles['data']));
        $this->assertEquals('FNZ6110', $vehicles['searched']['search']);
        $this->assertEquals('FNZ6110', $vehicles['searched']['vin']);
        $this->assertFalse($vehicles['searched']['isElasticSearch']);
    }

    public function testSearchWithAdditionalDataWithResultsWithRegistration()
    {
        // 4 Results
        $this->getMockVehicleRepositoryWithResult('search', $this->getVehicleMockObjects());

        $searchParam = new VehicleSearchParam('FNZ6110', 'registration');

        $service = $this->getMockService();
        $vehicles = $service->searchVehicleWithAdditionalData($searchParam);

        $this->assertEquals(4, $vehicles['resultCount']);
        $this->assertEquals(4, $vehicles['totalResultCount']);
        $this->assertEquals(4, count($vehicles['data']));
        $this->assertEquals('FNZ6110', $vehicles['searched']['search']);
        $this->assertEquals('FNZ6110', $vehicles['searched']['registration']);
        $this->assertFalse($vehicles['searched']['isElasticSearch']);
    }

    /**
     * @param $vehicleObject DvlaVehicle
     * @param $result array
     */
    private function assertionBetweenDvlaVehicleObjectAndDvlaVehicleResultArray($vehicleObject, $result)
    {
        $this->assertEquals($result['id'], $vehicleObject->getId());
        $this->assertEquals($result['registration'], $vehicleObject->getRegistration());
        $this->assertEquals($result['vin'], $vehicleObject->getVin());
        $this->assertEquals($result['cylinderCapacity'], $vehicleObject->getCylinderCapacity());
        $this->assertEquals($result['make'], $this->getMockDvlaMakeModelMap()->getMake()->getName());
        $this->assertEquals($result['model'], $this->getMockDvlaMakeModelMap()->getModel()->getName());
        $modelDetail = $vehicleObject->getModelDetail() ? $vehicleObject->getModelDetail()->getName() : null;
        $this->assertEquals($result['modelDetail'], $modelDetail);
        $this->assertEquals($result['primaryColour'], null);
        $this->assertEquals($result['secondaryColour'], null);
        $this->assertEquals($result['fuelType'], null);
        $this->assertEquals($result['bodyType'], $vehicleObject->getBodyType());
        $this->assertEquals($result['firstUsedDate'], DateTimeApiFormat::date($vehicleObject->getFirstUsedDate()));
        $this->assertEquals($result['transmissionType'], '');
        $this->assertEquals($result['weight'], $vehicleObject->getDesignedGrossWeight());
        $this->assertEquals($result['isDvla'], true);
    }

    private function getMockService()
    {
        return new VehicleSearchService(
            $this->mockAuthService,
            $this->mockVehicleRepository,
            $this->mockDvlaVehicleRepository,
            $this->mockDvlaVehicleImportChangesRepository,
            $this->mockMotTestRepository,
            $this->mockTesterService,
            $this->getMockVehicleCatalog(),
            $this->paramObfuscator,
            $this->vehicleSearchFuzzyEnabled
        );
    }

    private function getMockMotTestRepositoryWithResult($method, $result)
    {
        $this->mockVehicleRepository->expects($this->any())
            ->method($method)
            ->willReturn($result);
    }

    private function getMockVehicleRepositoryWithResult($method, $result)
    {
        $this->mockVehicleRepository->expects($this->any())
                                    ->method($method)
                                    ->willReturn($result);
    }

    private function getMockDvlaVehicleRepositoryWithResult($method, $result)
    {
        $this->mockDvlaVehicleRepository->expects($this->any())
                                        ->method($method)
                                        ->willReturn($result);
    }

    private function getMockVehicleCatalog()
    {
        $this->mockVehicleCatalog->expects($this->any())
                                  ->method('extractColourForCode')
                                  ->willReturn(
                                      (new Colour())->setId(1)->setName('Red')
                                  );

        $this->mockVehicleCatalog->expects($this->any())
                                 ->method('extractOptionalColourForCode')
                                 ->willReturn(
                                     (new Colour())->setId(1)->setName('Red')
                                 );

        $this->mockVehicleCatalog->expects($this->any())
                                 ->method('findBodyTypeByCode')
                                 ->willReturn(
                                     (new BodyType())->setId(1)->setName('SE')
                                 );

        $this->mockVehicleCatalog->expects($this->any())
                                 ->method('getMakeModelMapByDvlaCode')
                                 ->willReturn(
                                     $this->getMockDvlaMakeModelMap()
                                 );

        return $this->mockVehicleCatalog;
    }

    private function getMockDvlaMakeModelMap()
    {
        $makeModelMap  = (new DvlaMakeModelMap())->setId(1)
                                                 ->setMake(
                                                    (new Make())->setId(1)->setName('BMW')
                                                 )
                                                 ->setModel(
                                                    (new Model())->setId(1)->setName('3 Series')
                                                 );
        return $makeModelMap;
    }

    private function getVehicleMockObjects()
    {
        $vehicleArray = [];
        $vehicleArray[] = VOF::vehicle(1)->setVin('JOTSNA');
        $vehicleArray[] = VOF::vehicle(2)->setVin('123456JOTSNA')
                                         ->setRegistration('DNZ110');
        $vehicleArray[] = VOF::vehicle(3);
        $vehicleArray[] = VOF::vehicle(4);

        return $vehicleArray;
    }

    private function getDvlaVehicleMockObjects()
    {
        $vehicleArray = [];
        $vehicleArray[] = VOF::dvlaVehicle(1)->setVin('DVLA1')
                                             ->setRegistration('DVLA21');
        $vehicleArray[] = VOF::dvlaVehicle(2)->setVin('DVLA2')
                                             ->setRegistration('DVLA22');

        return $vehicleArray;
    }

}
