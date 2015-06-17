<?php

namespace VehicleApiTest\Service;

use DataCatalogApi\Service\VehicleCatalogService;
use DvsaAuthorisation\Service\AuthorisationServiceInterface;
use DvsaCommon\Auth\MotAuthorisationServiceInterface;
use DvsaCommon\Date\DateTimeApiFormat;
use DvsaCommon\Dto\Vehicle\AbstractVehicleDto;
use DvsaCommon\Dto\Vehicle\DvlaVehicleDto;
use DvsaCommon\Dto\Vehicle\VehicleDto;
use DvsaCommon\Dto\VehicleClassification\VehicleClassDto;
use DvsaCommon\Enum\WeightSourceCode;
use DvsaCommon\Enum\VehicleClassCode;
use DvsaCommon\Enum\VehicleClassId;
use DvsaCommon\Obfuscate\ParamObfuscator;
use DvsaCommonApi\Service\Exception\OtpException;
use DvsaCommonApiTest\Service\AbstractServiceTestCase;
use DvsaCommonTest\Bootstrap;
use DvsaCommonTest\TestUtils\ArgCapture;
use DvsaCommonTest\TestUtils\MultiCallStubBuilder;
use DvsaCommonTest\TestUtils\XMock;
use DvsaEntities\Entity\DvlaVehicle;
use DvsaEntities\Entity\DvlaMakeModelMap;
use DvsaEntities\Entity\Make;
use DvsaEntities\Entity\Model;
use DvsaEntities\Entity\Person;
use DvsaEntities\Entity\Vehicle;
use DvsaEntities\Entity\VehicleV5C;
use DvsaEntities\Repository\DvlaVehicleImportChangesRepository;
use DvsaEntities\Repository\DvlaVehicleRepository;
use DvsaEntities\Repository\VehicleRepository;
use DvsaEntities\Repository\VehicleV5CRepository;
use Doctrine\ORM\EntityRepository;
use DvsaMotApi\Service\OtpService;
use DvsaMotApi\Service\Validator\VehicleValidator;
use DvsaMotApiTest\Factory\VehicleObjectsFactory as VOF;
use PHPUnit_Framework_MockObject_MockObject as MockObj;
use VehicleApi\Service\VehicleService;
use Zend\Http\Header\Date;

/**
 * it test functionality of class VehicleService
 *
 * @package VehicleApiTest\Service
 */
class VehicleServiceTest extends AbstractServiceTestCase
{
    const OTP_VALID = '123456';
    const OTP_INVALID = '000000';

    const VEHICLE_ID = 9999;
    const VEHICLE_ID_ENC = 'jq33IixSpBsx4rglOvxByg';

    /** @var MotAuthorisationServiceInterface|MockObj */
    private $mockAuthService;
    /** @var VehicleRepository|MockObj */
    private $mockVehicleRepository;
    /** @var VehicleV5CRepository|MockObj */
    private $mockVehicleV5CRepository;
    /** @var DvlaVehicleRepository|MockObj */
    private $mockDvlaVehicleRepository;
    /** @var DvlaVehicleImportChangesRepository|MockObj */
    private $mockDvlaVehicleImportChangesRepository;
    /** @var EntityRepository */
    private $mockDvlaMakeModelMapRepository;
    /** @var VehicleCatalogService|MockObj */
    private $mockVehicleCatalog;
    /** @var VehicleValidator|MockObj */
    private $mockValidator;
    /** @var  OtpService|MockObj */
    private $mockOtpService;
    /** @var ParamObfuscator */
    private $paramObfuscator;

    private $serviceManager;

    public function setUp()
    {
        $this->mockAuthService = XMock::of(AuthorisationServiceInterface::class, ['isGranted', 'assertGranted']);
        $this->mockVehicleRepository = XMock::of(VehicleRepository::class);
        $this->mockVehicleV5CRepository = XMock::of(VehicleV5CRepository::class);
        $this->mockDvlaVehicleRepository = XMock::of(DvlaVehicleRepository::class);
        $this->mockDvlaVehicleImportChangesRepository = XMock::of(DvlaVehicleImportChangesRepository::class);
        $this->mockVehicleCatalog = XMock::of(VehicleCatalogService::class);

        $this->mockDvlaMakeModelMapRepository = XMock::of(EntityRepository::class);

        $this->paramObfuscator = XMock::of(ParamObfuscator::class);
        $this->mockValidator = new VehicleValidator();
        $this->mockOtpService = XMock::of(OtpService::class);
        $this->mockOtpService->expects($this->any())->method('authenticate')->will(
            MultiCallStubBuilder::of()
                ->add(self::OTP_INVALID, $this->throwException(new OtpException(0, 5)))
                ->add(null, $this->throwException(new OtpException(5, 5)))
                ->add(self::OTP_VALID, true)
                ->build()
        );
    }

    public function testGetVehicleById()
    {
        $id = 2;
        $entity = 'VEHICLE_ENTITY';

        $this->mockVehicleRepository->expects($this->once())
            ->method('get')
            ->with($id)
            ->will($this->returnValue($entity));

        $service = $this->createService();

        $this->assertEquals($entity, $service->getVehicle($id));
    }

    public function testGetVehicleByIdShouldReturnVehicleEntity()
    {
        $vehicleId = 1;
        $vehicle = VOF::vehicle($vehicleId);
        $this->returningOn($this->mockVehicleRepository, $vehicle);

        $vehicleEntity = $this->createService()->getVehicle($vehicleId);

        $this->assertSame($vehicle, $vehicleEntity);
    }

    public function testGetVehicleDtoByIdShouldReturnVehicleDto()
    {
        $vehicle = VOF::vehicle(self::VEHICLE_ID);
        $this->returningOn($this->mockVehicleRepository, $vehicle);

        $vehicleDto = $this->createService()->getVehicleDto(self::VEHICLE_ID_ENC);

        $this->assertInstanceOf(VehicleDto::class, $vehicleDto);
        $this->assertVehicleEntityEqualsDto($vehicle, $vehicleDto);

        $this->assertEquals($vehicleDto->getId(), self::VEHICLE_ID_ENC);
        $this->assertNotEquals($vehicleDto->getId(), $vehicle->getId());
    }

    public function testGetDvlaVehicleDtoByIdShouldReturnDvlaVehicleDto()
    {
        $vehicle = VOF::dvlaVehicle(self::VEHICLE_ID);
        $this->returningOn($this->mockDvlaVehicleRepository, $vehicle);

        $colourCode = 'R';
        $secondaryColourCode = 'G';
        $this->returningOn(
            $this->mockVehicleCatalog,
            MultiCallStubBuilder::of()
                ->add([$colourCode, $this->anything()], VOF::colour(1, $colourCode))
                ->add([$secondaryColourCode, $this->anything()], VOF::colour(2, $secondaryColourCode))
                ->build(),
            'findColourByCode'
        );

        $map = (new DvlaMakeModelMap())
            ->setMake(VOF::make())
            ->setModel(VOF::model());

        $this->mockMethod(
            $this->mockVehicleCatalog, 'getMakeModelMapByDvlaCode', $this->once(), $this->returnValue($map)
        );
        $this->mockMethod($this->mockVehicleCatalog, 'findBodyTypeByCode', $this->once(), VOF::bodyType());

        $vehicleDto = $this->createService()->getDvlaVehicleData(self::VEHICLE_ID_ENC);

        $this->assertInstanceOf(DvlaVehicleDto::class, $vehicleDto);
        $this->assertDvlaVehicleEntityEqualsDto($vehicle, $vehicleDto);

        $this->assertEquals($vehicleDto->getId(), self::VEHICLE_ID_ENC);
        $this->assertNotEquals($vehicleDto->getId(), $vehicle->getId());
    }

    public function testCreateVtrAndV5CfromDvlaVehicleGivenDvlaVehicleShouldCreateVtrAndV5C()
    {
        $dvlaVehicle = VOF::dvlaVehicle();
        $vehicleClassCode = VehicleClassCode::CLASS_4;

        $vtrCapture = ArgCapture::create();

        $this->returningOn($this->mockVehicleCatalog, VOF::countryOfRegistration(3), 'getCountryOfRegistrationByCode');
        $this->returningOn(
            $this->mockVehicleCatalog,
            VOF::vehicleClass(VehicleClassId::CLASS_4, VehicleClassCode::CLASS_4),
            'getVehicleClassByCode'
        );
        $this->returningOn($this->mockDvlaVehicleRepository, $dvlaVehicle);
        $this->returningOn($this->mockVehicleCatalog, VOF::bodyType(), "findBodyTypeByCode");

        $colourCode = 'R';
        $secondaryColourCode = 'G';
        $this->returningOn(
            $this->mockVehicleCatalog,
            MultiCallStubBuilder::of()
                ->add([$colourCode, $this->anything()], VOF::colour(1, $colourCode))
                ->add([$secondaryColourCode, $this->anything()], VOF::colour(2, $secondaryColourCode))
                ->build(),
            'getColourByCode'
        );
        $this->returningOn($this->mockVehicleCatalog, VOF::fuelType(), 'findFuelTypeByPropulsionCode');
        $this->returningOn($this->mockVehicleCatalog, VOF::model(), 'getModelByCode');
        $this->returningOn($this->mockVehicleCatalog, VOF::make(), 'findMakeByCode');

        $map = (new DvlaMakeModelMap())
            ->setModel(VOF::model());
        $this
            ->mockVehicleCatalog
            ->expects($this->once())
            ->method('getMakeModelMapByDvlaCode')
            ->will($this->returnValue($map));

        $this->returningOn(
            $this->mockVehicleCatalog, VOF::weightSource(WeightSourceCode::DGW), 'getWeightSourceByCode'
        );

        $this->mockVehicleRepository
            ->expects($this->any())
            ->method('save')
            ->with($vtrCapture());

        $this->mockVehicleV5CRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(VehicleV5C::class));

        $vehicleId = $this->paramObfuscator->obfuscateEntry(ParamObfuscator::ENTRY_VEHICLE_ID, $dvlaVehicle->getId());
        $this->createService()->createVtrAndV5CFromDvlaVehicle($vehicleId, $vehicleClassCode);

        /** @var Vehicle $v */
        $v = $vtrCapture->get();

        $this->assertEquals($dvlaVehicle->getVin(), $v->getVin());
        $this->assertEquals($dvlaVehicle->getRegistration(), $v->getRegistration());
        $this->assertEquals($dvlaVehicle->getManufactureDate(), $v->getManufactureDate());
        $this->assertEquals($dvlaVehicle->getFirstRegistrationDate(), $v->getFirstUsedDate());
        $this->assertEquals($dvlaVehicle->getPrimaryColour(), $v->getColour()->getCode());
        $this->assertEquals($dvlaVehicle->getSecondaryColour(), $v->getSecondaryColour()->getCode());
        $this->assertEquals($dvlaVehicle->getMakeName(), $v->getMakeName());
        $this->assertEquals($dvlaVehicle->getModelName(), $v->getModelName());
        $this->assertEquals($dvlaVehicle->getCylinderCapacity(), $v->getCylinderCapacity());
        $this->assertEquals($dvlaVehicle->getBodyType(), $v->getBodyType()->getCode());
        $this->assertEquals($dvlaVehicle->getFuelType(), $v->getFuelType()->getCode());
        $this->assertEquals($dvlaVehicle->getDesignedGrossWeight(), $v->getWeight());
        $this->assertEquals($dvlaVehicle->getId(), $v->getDvlaVehicleId());
        $this->assertEquals(WeightSourceCode::DGW, $v->getWeightSource()->getCode());
    }

    public function testCreateVtrAndV5CfromDvlaVehicleGivenDvlaVehicleShouldCountWeightBasedOnUnladenWeight()
    {
        $dvlaVehicle = VOF::dvlaVehicle();
        $dvlaVehicle->setDesignedGrossWeight(null);
        $dvlaVehicle->setUnladenWeight(1000);

        $vehicleClassCode = VehicleClassCode::CLASS_4;
        $vtrCapture = ArgCapture::create();

        $this->returningOn($this->mockVehicleCatalog, VOF::countryOfRegistration(3), 'getCountryOfRegistrationByCode');
        $this->returningOn(
            $this->mockVehicleCatalog,
            VOF::vehicleClass(VehicleClassId::CLASS_4, VehicleClassCode::CLASS_4),
            'getVehicleClassByCode'
        );
        $this->returningOn($this->mockDvlaVehicleRepository, $dvlaVehicle);
        $this->returningOn($this->mockVehicleCatalog, VOF::bodyType(), "findBodyTypeByCode");
        $this->returningOn(
            $this->mockVehicleCatalog, VOF::weightSource(WeightSourceCode::UNLADEN), 'getWeightSourceByCode'
        );

        $colourCode = 'R';
        $secondaryColourCode = 'G';
        $this->returningOn(
            $this->mockVehicleCatalog,
            MultiCallStubBuilder::of()
                ->add([$colourCode, $this->anything()], VOF::colour(1, $colourCode))
                ->add([$secondaryColourCode, $this->anything()], VOF::colour(2, $secondaryColourCode))
                ->build(),
            'getColourByCode'
        );
        $this->returningOn($this->mockVehicleCatalog, VOF::model(), 'getModelByCode');

        $this->mockVehicleRepository
            ->expects($this->any())
            ->method('save')
            ->with($vtrCapture());

        $this->mockVehicleV5CRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(VehicleV5C::class));

        $this->createService()->createVtrAndV5CFromDvlaVehicle(self::VEHICLE_ID_ENC, $vehicleClassCode);

        /** @var Vehicle $v */
        $v = $vtrCapture->get();

        $this->assertEquals(1140, $v->getWeight());
        $this->assertEquals(WeightSourceCode::UNLADEN, $v->getWeightSource()->getCode());
    }

    public function testCreateVtrAndV5CfromDvlaVehicleGivenDvlaVehicleShouldCreateLinkBetweenDvlaAndVtr()
    {
        $dvlaVehicle = VOF::dvlaVehicle();
        $vehicleClassCode = VehicleClassCode::CLASS_4;

        $dvlaCapture = ArgCapture::create();

        $this->returningOn($this->mockVehicleCatalog, VOF::countryOfRegistration(3), 'getCountryOfRegistrationByCode');
        $this->returningOn(
            $this->mockVehicleCatalog,
            VOF::vehicleClass(VehicleClassId::CLASS_4, VehicleClassCode::CLASS_4),
            'getVehicleClassByCode'
        );
        $this->returningOn($this->mockDvlaVehicleRepository, $dvlaVehicle);
        $this->returningOn($this->mockVehicleCatalog, VOF::bodyType(), "findBodyTypeByCode");

        $colourCode = 'R';
        $secondaryColourCode = 'G';
        $this->returningOn(
            $this->mockVehicleCatalog,
            MultiCallStubBuilder::of()
                ->add([$colourCode, $this->anything()], VOF::colour(1, $colourCode))
                ->add([$secondaryColourCode, $this->anything()], VOF::colour(2, $secondaryColourCode))
                ->build(),
            'getColourByCode'
        );
        $this->returningOn($this->mockVehicleCatalog, VOF::model(), 'getModelByCode');

        $this->mockDvlaVehicleRepository
            ->expects($this->any())
            ->method('save')
            ->with($dvlaCapture());

        $this->mockVehicleV5CRepository
            ->expects($this->once())
            ->method('save')
            ->with($this->isInstanceOf(VehicleV5C::class));

        $this->createService()->createVtrAndV5CFromDvlaVehicle(self::VEHICLE_ID_ENC, $vehicleClassCode);

        /** @var DvlaVehicle $savedDvla */
        $savedDvla = $dvlaCapture->get();

        $this->assertNotNull($savedDvla->getVehicle());
    }

    /**
     * If the dvla_vehicle.recent_v5_document_number is NULL a new VehicleV5C entity should *not* be created and
     * persisted.
     */
    public function testCreateVtrAndV5CfromDvlaVehicleGivenDvlaVehicleShouldNotCreateV5CIfDvlaFieldIsMissing()
    {
        $dvlaVehicle = VOF::dvlaVehicle();
        $dvlaVehicle->setV5DocumentNumber(null);
        $vehicleClassCode = VehicleClassCode::CLASS_4;

        $vtrCapture = ArgCapture::create();

        $this->returningOn($this->mockVehicleCatalog, VOF::countryOfRegistration(3), 'getCountryOfRegistrationByCode');
        $this->returningOn(
            $this->mockVehicleCatalog,
            VOF::vehicleClass(VehicleClassId::CLASS_4, VehicleClassCode::CLASS_4),
            'getVehicleClassByCode'
        );
        $this->returningOn($this->mockDvlaVehicleRepository, $dvlaVehicle);
        $this->returningOn($this->mockVehicleCatalog, VOF::bodyType(), "findBodyTypeByCode");

        $colourCode = 'R';
        $secondaryColourCode = 'G';
        $this->returningOn(
            $this->mockVehicleCatalog,
            MultiCallStubBuilder::of()
                ->add([$colourCode, $this->anything()], VOF::colour(1, $colourCode))
                ->add([$secondaryColourCode, $this->anything()], VOF::colour(2, $secondaryColourCode))
                ->build(),
            'getColourByCode'
        );
        $this->returningOn($this->mockVehicleCatalog, VOF::fuelType(), 'findFuelTypeByPropulsionCode');
        $this->returningOn($this->mockVehicleCatalog, VOF::model(), 'getModelByCode');
        $this->returningOn($this->mockVehicleCatalog, VOF::make(), 'findMakeByCode');

        $map = (new DvlaMakeModelMap())
            ->setModel(VOF::model());
        $this
            ->mockVehicleCatalog
            ->expects($this->once())
            ->method('getMakeModelMapByDvlaCode')
            ->will($this->returnValue($map));

        $this->returningOn(
            $this->mockVehicleCatalog, VOF::weightSource(WeightSourceCode::DGW), 'getWeightSourceByCode'
        );

        $this->mockVehicleRepository
            ->expects($this->any())
            ->method('save')
            ->with($vtrCapture());

        $this->mockVehicleV5CRepository
            ->expects($this->never())
            ->method('save');

        $vehicleId = $this->paramObfuscator->obfuscateEntry(ParamObfuscator::ENTRY_VEHICLE_ID, $dvlaVehicle->getId());
        $this->createService()->createVtrAndV5CFromDvlaVehicle($vehicleId, $vehicleClassCode);

        /** @var Vehicle $v */
        $v = $vtrCapture->get();

        $this->assertEquals($dvlaVehicle->getVin(), $v->getVin());
        $this->assertEquals($dvlaVehicle->getRegistration(), $v->getRegistration());
        $this->assertEquals($dvlaVehicle->getManufactureDate(), $v->getManufactureDate());
        $this->assertEquals($dvlaVehicle->getFirstRegistrationDate(), $v->getFirstUsedDate());
        $this->assertEquals($dvlaVehicle->getPrimaryColour(), $v->getColour()->getCode());
        $this->assertEquals($dvlaVehicle->getSecondaryColour(), $v->getSecondaryColour()->getCode());
        $this->assertEquals($dvlaVehicle->getMakeName(), $v->getMakeName());
        $this->assertEquals($dvlaVehicle->getModelName(), $v->getModelName());
        $this->assertEquals($dvlaVehicle->getCylinderCapacity(), $v->getCylinderCapacity());
        $this->assertEquals($dvlaVehicle->getBodyType(), $v->getBodyType()->getCode());
        $this->assertEquals($dvlaVehicle->getFuelType(), $v->getFuelType()->getCode());
        $this->assertEquals($dvlaVehicle->getDesignedGrossWeight(), $v->getWeight());
        $this->assertEquals($dvlaVehicle->getId(), $v->getDvlaVehicleId());
        $this->assertEquals(WeightSourceCode::DGW, $v->getWeightSource()->getCode());
    }

    public function testVehicleRecordFromDvlaVehicleWithNoMakeOrModelCodeAndTextShouldBeUnknown()
    {
        $dvlaVehicle = VOF::dvlaVehicle();
        $dvlaVehicle->setMakeCode(null);
        $dvlaVehicle->setModelCode(null);
        $dvlaVehicle->setMakeInFull(null);

        $vehicleClassCode = VehicleClassCode::CLASS_4;

        $dvlaCapture = ArgCapture::create();

        $this->returningOn($this->mockVehicleCatalog, VOF::countryOfRegistration(3), 'getCountryOfRegistrationByCode');
        $this->returningOn(
            $this->mockVehicleCatalog,
            VOF::vehicleClass(VehicleClassId::CLASS_4, VehicleClassCode::CLASS_4),
            'getVehicleClassByCode'
        );
        $this->returningOn($this->mockDvlaVehicleRepository, $dvlaVehicle);
        $this->returningOn($this->mockVehicleCatalog, VOF::bodyType(), "findBodyTypeByCode");

        $colourCode = 'R';
        $secondaryColourCode = 'G';
        $this->returningOn(
            $this->mockVehicleCatalog,
            MultiCallStubBuilder::of()
                ->add([$colourCode, $this->anything()], VOF::colour(1, $colourCode))
                ->add([$secondaryColourCode, $this->anything()], VOF::colour(2, $secondaryColourCode))
                ->build(),
            'getColourByCode'
        );
        $this->returningOn($this->mockVehicleCatalog, VOF::model(), 'getModelByCode');

        $this->mockDvlaVehicleRepository
            ->expects($this->any())
            ->method('save')
            ->with($dvlaCapture());

        $this->createService()->createVtrAndV5CFromDvlaVehicle(self::VEHICLE_ID_ENC, $vehicleClassCode);

        /** @var DvlaVehicle $savedDvla */
        $savedDvla = $dvlaCapture->get();

        $this->assertNotNull($savedDvla->getVehicle());
        $this->assertEquals($savedDvla->getVehicle()->getMakeName(), 'Unknown');
    }

    public function testVehicleRecordFromDvlaVehicleWithFullMakeTextShouldBeFullMakeText()
    {
        $dvlaVehicle = VOF::dvlaVehicle();
        $dvlaVehicle->setMakeCode(null);
        $dvlaVehicle->setModelCode(null);
        $dvlaVehicle->setMake(null);
        $dvlaVehicle->setModel(null);
        $dvlaVehicle->setMakeInFull('Ford Supercharger');

        $vehicleClassCode = VehicleClassCode::CLASS_4;
        $dvlaCapture = ArgCapture::create();

        $this->returningOn($this->mockVehicleCatalog, VOF::countryOfRegistration(3), 'getCountryOfRegistrationByCode');
        $this->returningOn(
            $this->mockVehicleCatalog,
            VOF::vehicleClass(VehicleClassId::CLASS_4, VehicleClassCode::CLASS_4),
            'getVehicleClassByCode'
        );
        $this->returningOn($this->mockDvlaVehicleRepository, $dvlaVehicle);
        $this->returningOn($this->mockVehicleCatalog, VOF::bodyType(), "findBodyTypeByCode");

        $colourCode = 'R';
        $secondaryColourCode = 'G';
        $this->returningOn(
            $this->mockVehicleCatalog,
            MultiCallStubBuilder::of()
                ->add([$colourCode, $this->anything()], VOF::colour(1, $colourCode))
                ->add([$secondaryColourCode, $this->anything()], VOF::colour(2, $secondaryColourCode))
                ->build(),
            'getColourByCode'
        );
        $this->returningOn($this->mockVehicleCatalog, VOF::model(), 'getModelByCode');

        $this->mockDvlaVehicleRepository
            ->expects($this->any())
            ->method('save')
            ->with($dvlaCapture());

        $this->createService()->createVtrAndV5CFromDvlaVehicle(self::VEHICLE_ID_ENC, $vehicleClassCode);

        /** @var DvlaVehicle $savedDvla */
        $savedDvla = $dvlaCapture->get();

        $this->assertNotNull($savedDvla->getVehicle());
        $this->assertEquals($savedDvla->getVehicle()->getMakeName(), 'Ford Supercharger');
    }

    public function testVehicleRecordFromDvlaVehicleWithNoMakeButWithModelCodeShouldBeUnknownWithModel()
    {
        $dvlaVehicle = VOF::dvlaVehicle();
        $dvlaVehicle->setMakeCode(null);
        $dvlaVehicle->setMake(null);
        $dvlaVehicle->setModelCode('ABC');
        $dvlaVehicle->setModel(
            (new Model())->setCode('ABC')
                         ->setId(1)
                         ->setName('DB9')
        );
        $dvlaVehicle->setMakeInFull(null);

        $vehicleClassCode = VehicleClassCode::CLASS_4;

        $dvlaCapture = ArgCapture::create();

        $this->returningOn($this->mockVehicleCatalog, VOF::countryOfRegistration(3), 'getCountryOfRegistrationByCode');
        $this->returningOn(
            $this->mockVehicleCatalog,
            VOF::vehicleClass(VehicleClassId::CLASS_4, VehicleClassCode::CLASS_4),
            'getVehicleClassByCode'
        );
        $this->returningOn($this->mockDvlaVehicleRepository, $dvlaVehicle);
        $this->returningOn($this->mockVehicleCatalog, VOF::bodyType(), "findBodyTypeByCode");

        $colourCode = 'R';
        $secondaryColourCode = 'G';
        $this->returningOn(
            $this->mockVehicleCatalog,
            MultiCallStubBuilder::of()
                ->add([$colourCode, $this->anything()], VOF::colour(1, $colourCode))
                ->add([$secondaryColourCode, $this->anything()], VOF::colour(2, $secondaryColourCode))
                ->build(),
            'getColourByCode'
        );
        $this->returningOn($this->mockVehicleCatalog, VOF::model(), 'getModelByCode');

        $this->mockDvlaVehicleRepository
            ->expects($this->any())
            ->method('save')
            ->with($dvlaCapture());

        $this->createService()->createVtrAndV5CFromDvlaVehicle(self::VEHICLE_ID_ENC, $vehicleClassCode);

        /** @var DvlaVehicle $savedDvla */
        $savedDvla = $dvlaCapture->get();

        $this->assertNotNull($savedDvla->getVehicle());
        $this->assertEquals($savedDvla->getVehicle()->getMakeName(), 'Unknown');
    }

    public function testVehicleRecordFromDvlaVehicleWithMakeButWithNoModelCodeShouldBeMakeWithUnknown()
    {
        $dvlaVehicle = VOF::dvlaVehicle();
        $dvlaVehicle->setMakeCode('ABC');
        $dvlaVehicle->setMake(
            (new Make())->setCode('ABC')
                        ->setName('Aston Martin')
                        ->setId(1)
        );
        $dvlaVehicle->setModelCode(null);
        $dvlaVehicle->setMakeInFull(null);

        $vehicleClassCode = VehicleClassCode::CLASS_4;

        $dvlaCapture = ArgCapture::create();

        $this->returningOn($this->mockVehicleCatalog, VOF::countryOfRegistration(3), 'getCountryOfRegistrationByCode');
        $this->returningOn(
            $this->mockVehicleCatalog,
            VOF::vehicleClass(VehicleClassId::CLASS_4, VehicleClassCode::CLASS_4),
            'getVehicleClassByCode'
        );
        $this->returningOn($this->mockDvlaVehicleRepository, $dvlaVehicle);
        $this->returningOn($this->mockVehicleCatalog, VOF::bodyType(), "findBodyTypeByCode");

        $colourCode = 'R';
        $secondaryColourCode = 'G';
        $this->returningOn(
            $this->mockVehicleCatalog,
            MultiCallStubBuilder::of()
                ->add([$colourCode, $this->anything()], VOF::colour(1, $colourCode))
                ->add([$secondaryColourCode, $this->anything()], VOF::colour(2, $secondaryColourCode))
                ->build(),
            'getColourByCode'
        );
        $this->returningOn($this->mockVehicleCatalog, VOF::model(), 'getModelByCode');

        $this->mockDvlaVehicleRepository
            ->expects($this->any())
            ->method('save')
            ->with($dvlaCapture());

        $this->createService()->createVtrAndV5CFromDvlaVehicle(self::VEHICLE_ID_ENC, $vehicleClassCode);

        /** @var DvlaVehicle $savedDvla */
        $savedDvla = $dvlaCapture->get();

        $this->assertNotNull($savedDvla->getVehicle());
        $this->assertEquals($savedDvla->getVehicle()->getMakeName(), 'Unknown');
    }

    public function testLogDvlaVehicleImportChangesShouldSaveImportChangesData()
    {
        $tester = new Person();
        $tester->setId(1);

        $vehicle = VOF::vehicle();
        $primaryColourCode = 'A';
        $secondaryColourCode = 'B';
        $fuelTypeCode = 'PE';
        $vehicleClassCode = 22;
        $changesCapture = ArgCapture::create();

        $this->mockDvlaVehicleImportChangesRepository->expects($this->any())->method('save')->with($changesCapture());
        $this->returningOn(
            $this->mockVehicleCatalog,
            VOF::vehicleClass(VehicleClassId::CLASS_4, VehicleClassCode::CLASS_4),
            'getVehicleClass'
        );

        $this->returningOn(
            $this->mockVehicleCatalog,
            MultiCallStubBuilder::of()
                ->add([1, $this->anything()], VOF::colour(1, $primaryColourCode))
                ->add([2, $this->anything()], VOF::colour(2, $secondaryColourCode))
                ->build(),
            'getColour'
        );

        $this->returningOn($this->mockVehicleCatalog, VOF::fuelType(), 'getFuelType');
        $this->returningOn($this->mockVehicleCatalog, VOF::model(), 'getModelByCode');

        $this->createService()->logDvlaVehicleImportChanges(
            $tester,
            $vehicle,
            $vehicleClassCode,
            $primaryColourCode,
            $secondaryColourCode,
            $fuelTypeCode
        );

        /** @var \DvsaEntities\Entity\DvlaVehicleImportChangeLog $dvlaImportChanges */
        $dvlaImportChanges = $changesCapture->get();

        $this->assertEquals($vehicle, $dvlaImportChanges->getVehicle());
        $this->assertEquals($primaryColourCode, $dvlaImportChanges->getColour());
        $this->assertEquals($secondaryColourCode, $dvlaImportChanges->getSecondaryColour());
        $this->assertEquals($fuelTypeCode, $dvlaImportChanges->getFuelType());
    }

    /**
     * @param Vehicle $entity
     * @param AbstractVehicleDto  $dto
     */
    private function assertVehicleEntityEqualsDto(Vehicle $entity, AbstractVehicleDto $dto)
    {
        $this->assertEquals($entity->getVin(), $dto->getVin());
        $this->assertEquals($entity->getRegistration(), $dto->getRegistration());
        $this->assertEquals($entity->getCylinderCapacity(), $dto->getCylinderCapacity());

        $this->assertEquals($entity->getYear(), $dto->getYear());
        $this->assertEquals(
            DateTimeApiFormat::date($entity->getManufactureDate()),
            $dto->getManufactureDate()
        );
        $this->assertEquals(
            DateTimeApiFormat::date($entity->getFirstRegistrationDate()),
            $dto->getFirstRegistrationDate()
        );
        $this->assertEquals(
            DateTimeApiFormat::date($entity->getFirstUsedDate()),
            $dto->getFirstUsedDate()
        );

        $this->assertInstanceOf(\DateTime::class, $entity->getFirstRegistrationDate());
        $this->assertEquals(
            DateTimeApiFormat::date($entity->getFirstRegistrationDate()),
            $dto->getFirstRegistrationDate()
        );

        $makeName = $entity->getMakeName();
        $dto->setMakeName($makeName);
        $this->assertEquals($makeName, $dto->getMakeName());

        $modelName = $entity->getModelName();
        $dto->setModelName($modelName);
        $this->assertEquals($modelName, $dto->getModelName());

        $vehicleClass = $entity->getVehicleClass();
        $vehicleClassDto = new VehicleClassDto();
        $vehicleClassDto
            ->setId($vehicleClass->getId())
            ->setCode($vehicleClass->getCode())
            ->setName($vehicleClass->getName());

        $this->assertEquals($vehicleClassDto, $dto->getVehicleClass());

        $colour = $entity->getColour();

        $this->assertNotNull($colour);
        $this->assertNotNull($entity->getSecondaryColour());

        //  --  check colour --
        $expect = $colour;
        $actual = $dto->getColour();
        $this->assertEquals($expect->getCode(), $actual->getCode());
        $this->assertEquals($expect->getName(), $actual->getName());

        //  --  check secondary colour --
        $expect = $entity->getSecondaryColour();
        $actual = $dto->getColourSecondary();
        $this->assertEquals($expect->getCode(), $actual->getCode());
        $this->assertEquals($expect->getName(), $actual->getName());

        //  --  check body type --
        $expect = $entity->getBodyType();
        $actual = $dto->getBodyType();
        $this->assertEquals($expect->getId(), $actual->getId());
        $this->assertEquals($expect->getName(), $actual->getName());

        //  --  check transmission type --
        $expect = $entity->getTransmissionType();
        $actual = $dto->getTransmissionType();
        $this->assertEquals($expect->getId(), $actual->getId());
        $this->assertEquals($expect->getName(), $actual->getName());
    }

    /**
     * @param DvlaVehicle $entity
     * @param AbstractVehicleDto  $dto
     */
    private function assertDvlaVehicleEntityEqualsDto(DvlaVehicle $entity, AbstractVehicleDto $dto)
    {
        $this->assertEquals($entity->getVin(), $dto->getVin());
        $this->assertEquals($entity->getRegistration(), $dto->getRegistration());
        $this->assertEquals($entity->getCylinderCapacity(), $dto->getCylinderCapacity());

        $this->assertInstanceOf(\DateTime::class, $entity->getFirstRegistrationDate());
        $this->assertEquals(
            DateTimeApiFormat::date($entity->getFirstRegistrationDate()),
            $dto->getFirstRegistrationDate()
        );

        $this->assertEquals($entity->getMakeName(), $dto->getMakeName());
        $this->assertEquals($entity->getModelName(), $dto->getModelName());

        $this->assertNotNull($entity->getPrimaryColour());
        $this->assertNotNull($entity->getSecondaryColour());

        //  --  check colour --
        $expectedColourCode = $entity->getPrimaryColour();
        $actual = $dto->getColour();
        $this->assertEquals($expectedColourCode, $actual->getCode());

        //  --  check secondary colour --
        $expectedSecondaryColourCode = $entity->getSecondaryColour();
        $actual = $dto->getColourSecondary();
        $this->assertEquals($expectedSecondaryColourCode, $actual->getCode());

        //  --  check body type --
        $expect = $entity->getBodyType();
        $actual = $dto->getBodyType();
        $this->assertEquals($expect, $actual->getCode());
    }

    public function testCreateGivenVehicleDataShouldSaveIt()
    {
        $inputData = self::dataCreateVehicle();
        $inputData['oneTimePassword'] = self::OTP_VALID;

        $colourId = 1;
        $colourCode = $inputData['colour'];
        $secondaryColourId = 2;
        $secondaryColourCode = $inputData['secondaryColour'];
        $countryOfRegistrationId = $inputData['countryOfRegistration'];
        $transTypeId = $inputData['transmissionType'];
        $fuelTypeCode = $inputData['fuelType'];
        $vehicleClassCode = $inputData['testClass'];
        $makeId = $inputData['make'];
        $modelId = $inputData['model'];
        $modelDetailId = $inputData['modelType'];

        $vehicleCapture = ArgCapture::create();
        $this->mockVehicleRepository->expects($this->any())->method('save')->with($vehicleCapture());

        $this->mockVehicleRepository->expects($this->any())
                                    ->method('save')
                                    ->with($vehicleCapture())
                                    ->willReturn(VOF::vehicle(5));

        $this->returningOn(
            $this->mockVehicleCatalog,
            MultiCallStubBuilder::of()
                ->add([$colourCode, $this->anything()], VOF::colour($colourId, $colourCode))
                ->add([$secondaryColourCode, $this->anything()], VOF::colour($secondaryColourId, $secondaryColourCode))
                ->build(),
            'getColourByCode'
        );

        $this->returningOn(
            $this->mockVehicleCatalog,
            VOF::vehicleClass(VehicleClassId::CLASS_4, VehicleClassCode::CLASS_4),
            'getVehicleClassByCode'
        );
        $this->returningOn(
            $this->mockVehicleCatalog,
            VOF::countryOfRegistration($countryOfRegistrationId),
            'getCountryOfRegistration'
        );
        $this->returningOn($this->mockVehicleCatalog, VOF::transmissionType($transTypeId), 'getTransmissionType');
        $this->returningOn($this->mockVehicleCatalog, VOF::fuelType(), 'getFuelTypeByCode');
        $this->returningOn($this->mockVehicleCatalog, VOF::make($makeId), 'getMakeByCode');
        $this->returningOn(
            $this->mockVehicleCatalog, VOF::model($modelId, 'COPER', 'Cooper', VOF::make($makeId)),
            'findModel'
        );
        $this->returningOn($this->mockVehicleCatalog, VOF::modelDetail($modelDetailId), 'getModelDetail');

        $this->paramObfuscator = XMock::of(ParamObfuscator::class);
        $this->paramObfuscator->expects($this->any())
                              ->method('obfuscate')
                              ->withAnyParameters()
                              ->will($this->returnValue(1));

        $this->createService()->create($inputData);

        /** @var Vehicle $v */
        $v = $vehicleCapture->get();
        $this->assertEquals($colourId, $v->getColour()->getId());
        $this->assertEquals($secondaryColourId, $v->getSecondaryColour()->getId());
        $this->assertEquals($countryOfRegistrationId, $v->getCountryOfRegistration()->getId());
        $this->assertEquals($transTypeId, $v->getTransmissionType()->getId());
        $this->assertEquals($fuelTypeCode, $v->getFuelType()->getCode());
        $this->assertEquals($vehicleClassCode, $v->getVehicleClass()->getCode());
        $this->assertEquals($makeId, $v->getMake()->getId());
        $this->assertEquals($modelId, $v->getModel()->getId());
        $this->assertEquals($modelDetailId, $v->getModelDetail()->getId());
        $this->assertEquals(
            $inputData['manufactureDate'],
            DateTimeApiFormat::date($v->getManufactureDate())
        );
        $this->assertEquals(
            $inputData['firstRegistrationDate'],
            DateTimeApiFormat::date($v->getFirstRegistrationDate())
        );
        $this->assertEquals(
            $inputData['dateOfFirstUse'],
            DateTimeApiFormat::date($v->getFirstUsedDate())
        );
        $this->assertEquals($inputData['cylinderCapacity'], $v->getCylinderCapacity());
        $this->assertEquals($inputData['registrationNumber'], $v->getRegistration());
        $this->assertEquals($inputData['vin'], $v->getVin());
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\OtpException
     */
    public function testCreateCalledByUserWithoutPermissionAndInvalidOtpThrowsAnException()
    {
        $input = $this->dataCreateVehicle();
        $input['oneTimePassword'] = self::OTP_INVALID;

        $this->createService()->create($input);
    }

    /**
     * @expectedException \DvsaCommonApi\Service\Exception\OtpException
     */
    public function testCreateCalledByUserWithoutPermissionRequiresOneTimePassword()
    {
        $input = $this->dataCreateVehicle();

        $this->createService()->create($input);
    }

    private static function dataCreateVehicle()
    {
        return [
            'vin'                   => VOF::EXAMPLE_VIN,
            'registrationNumber'    => VOF::EXAMPLE_VRM,
            'cylinderCapacity'      => 1234,
            'manufactureDate'       => '1990-12-12',
            'firstRegistrationDate' => '1990-12-23',
            'dateOfFirstUse'        => '2000-12-12',
            'make'                  => 1,
            'makeOther'             => '',
            'model'                 => 2,
            'modelOther'            => '',
            'modelType'             => 3,
            'colour'                => 'R',
            'fuelType'              => "PE",
            'testClass'             => VehicleClassCode::CLASS_4,
            'countryOfRegistration' => 9,
            'transmissionType'      => 10,
            'secondaryColour'       => 'G',
        ];
    }

    private function createService()
    {
        return new VehicleService(
            $this->mockAuthService,
            $this->mockVehicleRepository,
            $this->mockVehicleV5CRepository,
            $this->mockDvlaVehicleRepository,
            $this->mockDvlaVehicleImportChangesRepository,
            $this->mockDvlaMakeModelMapRepository,
            $this->mockVehicleCatalog,
            $this->mockValidator,
            $this->mockOtpService,
            $this->paramObfuscator
        );
    }

    private function returningOn(MockObj $repo, $returnObject, $method = 'get')
    {
        $repo->expects($this->any())->method($method)->will(
            is_a($returnObject, \PHPUnit_Framework_MockObject_Stub::class)
            ? $returnObject
            : $this->returnValue($returnObject)
        );
    }
}
