<?php

namespace VehicleTest\UpdateVehicleProperty\ViewModel\Builder;

use Core\ViewModel\Header\HeaderTertiaryList;
use Dvsa\Mot\ApiClient\Resource\Item\DvsaVehicle;
use Vehicle\UpdateVehicleProperty\ViewModel\Builder\VehicleTertiaryTitleBuilder;

class VehicleTertiaryTitleBuilderTest extends \PHPUnit_Framework_TestCase
{
    const REGISTRATION = '121212';
    const VIN = 'qwerqwer';
    const MAKE_NAME = 'BMW';
    const MODEL_NAME = 'F30';

    public function testHeaderGeneration()
    {
        $builder = new VehicleTertiaryTitleBuilder();
        $header = $builder->getTertiaryTitleForVehicle(new DvsaVehicle($this->getVehicle()));

        $this->assertInstanceOf(HeaderTertiaryList::class, $header);
        $elements = $header->getElements();

        $this->assertEquals(true, $elements[0]->isBold());
        $this->assertEquals(false, $elements[1]->isBold());
        $this->assertEquals(false, $elements[2]->isBold());
        $this->assertEquals(self::MAKE_NAME . ', ' . self::MODEL_NAME, $elements[0]->getText());
        $this->assertEquals(self::REGISTRATION, $elements[1]->getText());
        $this->assertEquals(self::VIN, $elements[2]->getText());
    }

    private function getVehicle()
    {
        return json_decode(json_encode([
            'registration' => self::REGISTRATION,
            'vin' => self::VIN,
            'make' => self::MAKE_NAME,
            'model' => self::MODEL_NAME,
        ]));
    }
}