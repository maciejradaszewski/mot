<?php

namespace VehicleTest\Helper;

use Application\Service\CatalogService;
use Core\ViewModel\Gds\Table\GdsRow;
use Core\ViewModel\Gds\Table\GdsTable;
use Dvsa\Mot\ApiClient\Resource\Item\DvsaVehicle;
use DvsaCommon\Dto\Vehicle\VehicleExpiryDto;
use DvsaCommonTest\TestUtils\XMock;
use Vehicle\Helper\ColoursContainer;
use Vehicle\Helper\VehicleInformationTableBuilder;

class VehicleInformationTableBuilderTest extends \PHPUnit_Framework_TestCase
{
    const MAKE_NAME = 'RENAULT';
    const MODEL_NAME = 'CLIO';
    const VIN = '1M8GDM9AXKP042788';
    const REGISTRATION = 'FNZ6110';

    /** @var  VehicleInformationTableBuilder */
    private $tableGenerator;

    public function setUp()
    {
        $catalogService = XMock::of(CatalogService::class);
        $catalogService->expects($this->any())->method('getCountriesOfRegistrationByCode')->willReturn([
            'BE' => 'B (BE) - Belgium',
            'XUKN' => 'Not Known',
        ]);
        $this->tableGenerator = new VehicleInformationTableBuilder($catalogService);
    }

    private function assertRowContentEquals(GdsRow $gdsRow, $expectedContent){
        $this->assertEquals($expectedContent, $gdsRow->getValue()->getContent());
    }


    public function testSpecificationTableGeneration()
    {
        $vehicle = $this->getVehicle();
        $this->tableGenerator->setVehicle(new DvsaVehicle($vehicle));
        $this->tableGenerator->setExpiryDateInformation($this->getExpiryInfo());
        $table = $this->tableGenerator->getVehicleSpecificationGdsTable();

        $this->assertInstanceOf(GdsTable::class, $table);

        $this->assertRowContentEquals($table->getRow(0), 'RENAULT, CLIO');
        $this->assertRowContentEquals($table->getRow(1), 'Petrol, 1,700 cc');
        $this->assertRowContentEquals($table->getRow(2), 'Automatic');
        $this->assertRowContentEquals($table->getRow(3), 'Grey and Black');
        $this->assertRowContentEquals($table->getRow(4), '12,467 Kg');
        $this->assertRowContentEquals($table->getRow(5), '4');
        $this->assertRowContentEquals($table->getRow(6), '6 September 2016');
    }

    public function testSpecificationTableWithLessData()
    {
        $vehicle = $this->getVehicle();
        $vehicle->model = null;
        $vehicle->cylinderCapacity = null;
        $vehicle->colourSecondary = 'Not Stated';
        $vehicle->weight = null;

        $this->tableGenerator->setVehicle(new DvsaVehicle($vehicle));
        $this->tableGenerator->setExpiryDateInformation(new VehicleExpiryDto());
        $table = $this->tableGenerator->getVehicleSpecificationGdsTable();

        $this->assertRowContentEquals($table->getRow(0), 'RENAULT');
        $this->assertRowContentEquals($table->getRow(1), 'Petrol');
        $this->assertRowContentEquals($table->getRow(2), 'Automatic');
        $this->assertRowContentEquals($table->getRow(3), 'Grey');
        $this->assertRowContentEquals($table->getRow(4), VehicleInformationTableBuilder::EMPTY_VALUE_TEXT);
        $this->assertRowContentEquals($table->getRow(5), '4');
        $this->assertRowContentEquals($table->getRow(6), VehicleInformationTableBuilder::EMPTY_VALUE_TEXT);
    }


    public function testRegistrationTableGeneration()
    {
        $vehicle = $this->getVehicle();
        $this->tableGenerator->setVehicle(new DvsaVehicle($vehicle));
        $this->tableGenerator->setExpiryDateInformation($this->getExpiryInfo());
        $table = $this->tableGenerator->getVehicleRegistrationGdsTable();

        $this->assertInstanceOf(GdsTable::class, $table);

        $this->assertRowContentEquals($table->getRow(0), self::REGISTRATION);
        $this->assertRowContentEquals($table->getRow(1), self::VIN);
        $this->assertRowContentEquals($table->getRow(2), 'BE');
        $this->assertRowContentEquals($table->getRow(3), 'Yes');
        $this->assertRowContentEquals($table->getRow(4), '2 January 2004');
        $this->assertRowContentEquals($table->getRow(5), '3 January 2004');
        $this->assertRowContentEquals($table->getRow(6), '4 January 2004');
        $this->assertRowContentEquals($table->getRow(7), '11 January 2004');
    }

    public function testRegistrationTableGenerationWithLessData()
    {
        $vehicle = $this->getVehicle();
        $vehicle->isNewAtFirstReg = false;
        $vehicle->countryOfRegistration = 'Not Known';
        $vehicle->amendedOn = null;

        $this->tableGenerator->setVehicle(new DvsaVehicle($vehicle));
        $this->tableGenerator->setExpiryDateInformation($this->getExpiryInfo());
        $table = $this->tableGenerator->getVehicleRegistrationGdsTable();

        $this->assertRowContentEquals($table->getRow(2), 'Not Known');
        $this->assertRowContentEquals($table->getRow(3), 'No');
        $this->assertRowContentEquals($table->getRow(7), VehicleInformationTableBuilder::EMPTY_VALUE_TEXT);

    }

    private function getVehicle()
    {
        return json_decode(json_encode([
            'id' => 1,
            'amendedOn' => '2004-01-11',
            'registration' => self::REGISTRATION,
            'vin' => self::VIN,
            'emptyVrmReason' => NULL,
            'emptyVinReason' => NULL,
            'make' => self::MAKE_NAME,
            'model' => self::MODEL_NAME,
            'colour' => 'Grey',
            'colourSecondary' => 'Black',
            'countryOfRegistration' => 'B (BE) - Belgium',
            'fuelType' => 'Petrol',
            'vehicleClass' => '4',
            'bodyType' => '2 Door Saloon',
            'cylinderCapacity' => 1700,
            'transmissionType' => 'Automatic',
            'firstRegistrationDate' => '2004-01-03',
            'firstUsedDate' => '2004-01-04',
            'manufactureDate' => '2004-01-02',
            'isNewAtFirstReg' => true,
            'weight' => 12467,
            'version' => 2,
        ]));
    }

    private function getExpiryInfo()
    {
        return (new VehicleExpiryDto())
            ->setEarliestTestDateForPostdatingExpiryDate(new \DateTime('2017-09-06'))
            ->setExpiryDate(new \DateTime('2016-09-06'))
            ->setPreviousCertificateExists(true)
            ->setIsEarlierThanTestDateLimit(false);
    }
}