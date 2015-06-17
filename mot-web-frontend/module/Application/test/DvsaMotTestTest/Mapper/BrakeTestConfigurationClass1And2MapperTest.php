<?php

namespace DvsaMotTestTest\Mapper;

use DvsaCommon\Dto\BrakeTest\BrakeTestConfigurationClass1And2Dto;
use DvsaCommon\Dto\Common\MotTestDto;
use DvsaCommon\Enum\BrakeTestTypeCode;
use DvsaMotTest\Mapper\BrakeTestConfigurationClass1And2Mapper;
use PHPUnit_Framework_TestCase;

/**
 * Tests for BrakeTestConfigurationClass1And2Mapper
 */
class BrakeTestConfigurationClass1And2MapperTest extends PHPUnit_Framework_TestCase
{
    /** @var BrakeTestConfigurationClass1And2Mapper */
    private $mapper;

    public function setup()
    {
        $this->mapper = new BrakeTestConfigurationClass1And2Mapper();
    }

    public function testMapToDto()
    {
        $testData = [
            'brakeTestType' => BrakeTestTypeCode::ROLLER,
            'vehicleWeightFront' => '111',
            'vehicleWeightRear' => '222',
            'riderWeight' => '88',
            'sidecarWeight' => '333',
            'isSidecarAttached' => '1',
        ];
        $expected = (new BrakeTestConfigurationClass1And2Dto())
            ->setBrakeTestType(BrakeTestTypeCode::ROLLER)
            ->setVehicleWeightFront('111')
            ->setVehicleWeightRear('222')
            ->setRiderWeight('88')
            ->setSidecarWeight('333')
            ->setIsSidecarAttached(true);

        $actual = $this->mapper->mapToDto($testData);

        $this->assertEquals($expected, $actual);
    }

    public function testMapToDtoWithInvalidInput()
    {
        $this->setExpectedException('InvalidArgumentException');

        $this->mapper->mapToDto(null);
    }

    public function testMapToDefaultDto()
    {
        $motTest = new MotTestDto();
        $expected = (new BrakeTestConfigurationClass1And2Dto())
            ->setBrakeTestType(BrakeTestTypeCode::ROLLER)
            ->setVehicleWeightFront('')
            ->setVehicleWeightRear('')
            ->setRiderWeight('')
            ->setSidecarWeight('')
            ->setIsSidecarAttached(false);

        $actual = $this->mapper->mapToDefaultDto($motTest);

        $this->assertEquals($expected, $actual);
    }

    public function testMapToDefaultDtoWithSiteDefault()
    {
        $motTest = (new MotTestDto())->setVehicleTestingStation(
            ['defaultBrakeTestClass1And2' => BrakeTestTypeCode::GRADIENT]
        );
        $expected = (new BrakeTestConfigurationClass1And2Dto())
            ->setBrakeTestType(BrakeTestTypeCode::GRADIENT)
            ->setVehicleWeightFront('')
            ->setVehicleWeightRear('')
            ->setRiderWeight('')
            ->setSidecarWeight('')
            ->setIsSidecarAttached(false);

        $actual = $this->mapper->mapToDefaultDto($motTest);

        $this->assertEquals($expected, $actual);
    }
}
