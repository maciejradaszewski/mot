<?php

namespace DvsaMotTestTest\Mapper;

use DvsaCommon\Dto\BrakeTest\BrakeTestConfigurationClass3AndAboveDto;
use DvsaCommon\Dto\Common\MotTestDto;
use DvsaCommon\Dto\Vehicle\VehicleDto;
use DvsaCommon\Dto\VehicleClassification\VehicleClassDto;
use DvsaCommon\Enum\BrakeTestTypeCode;
use DvsaCommon\Enum\VehicleClassCode;
use DvsaMotTest\Mapper\BrakeTestConfigurationClass3AndAboveMapper;
use PHPUnit_Framework_TestCase;

/**
 * Tests for BrakeTestConfigurationClass3AndAboveMapper
 */
class BrakeTestConfigurationClass3AndAboveMapperTest extends PHPUnit_Framework_TestCase
{
    /** @var BrakeTestConfigurationClass3AndAboveMapper */
    private $mapper;

    public function setup()
    {
        $this->mapper = new BrakeTestConfigurationClass3AndAboveMapper();
    }

    public function testMapToDto()
    {
        $testData = $this->getStandardMapInputData();
        $expected = $this->getStandardMapOutputDto();

        $actual = $this->mapper->mapToDto($testData);

        $this->assertEquals($expected, $actual);
    }

    public function testMapToDtoWith2ServiceBrakeControls()
    {
        $testData = $this->getStandardMapInputData();
        $testData['serviceBrakeControlsCount'] = '2';

        $expected = $this->getStandardMapOutputDto()
            ->setServiceBrake2TestType(BrakeTestTypeCode::ROLLER)
            ->setServiceBrakeControlsCount(2);

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
        $expected = $this->getDefaultDto();

        $actual = $this->mapper->mapToDefaultDto($motTest);

        $this->assertEquals($expected, $actual);
    }

    public function testMapToDefaultDtoWithVehicleWeightDefault()
    {
        $vehicleWeight = '1800';
        $motTest = $this->getMotTestWithVehicleWithPresetWeight($vehicleWeight);
        $expected = $this->getDefaultDto()->setVehicleWeight($vehicleWeight);

        $actual = $this->mapper->mapToDefaultDto($motTest);

        $this->assertEquals($expected, $actual);
    }

    public function testMapToDefaultDtoWithSiteDefault()
    {
        $motTest = (new MotTestDto())->setVehicleTestingStation(
            [
                'defaultParkingBrakeTestClass3AndAbove' => BrakeTestTypeCode::GRADIENT,
                'defaultServiceBrakeTestClass3AndAbove' => BrakeTestTypeCode::DECELEROMETER
            ]
        );
        $expected = $this->getDefaultDto()
            ->setServiceBrake1TestType(BrakeTestTypeCode::DECELEROMETER)
            ->setParkingBrakeTestType(BrakeTestTypeCode::GRADIENT);

        $actual = $this->mapper->mapToDefaultDto($motTest);

        $this->assertEquals($expected, $actual);
    }

    /**
     * @return array
     */
    private function getStandardMapInputData()
    {
        return [
            'serviceBrake1TestType' => BrakeTestTypeCode::ROLLER,
            'positionOfSingleWheel' => BrakeTestConfigurationClass3AndAboveMapper::LOCATION_FRONT,
            'parkingBrakeTestType' => BrakeTestTypeCode::ROLLER,
            'weightType' => 'vsi',
            'vehicleWeight' => '1400',
            'weightIsUnladen' => '1',
            'brakeLineType' => BrakeTestConfigurationClass3AndAboveMapper::BRAKE_LINE_TYPE_SINGLE,
            'vehiclePurposeType' => BrakeTestConfigurationClass3AndAboveMapper::VEHICLE_PURPOSE_TYPE_COMMERCIAL,
            'parkingBrakeWheelsCount' => '1',
            'serviceBrakeControlsCount' => '1',
            'numberOfAxles' => '2',
            'parkingBrakeNumberOfAxles' => '1',
        ];
    }

    /**
     * @return BrakeTestConfigurationClass3AndAboveDto
     */
    private function getStandardMapOutputDto()
    {
        return (new BrakeTestConfigurationClass3AndAboveDto())
            ->setServiceBrake1TestType(BrakeTestTypeCode::ROLLER)
            ->setServiceBrake2TestType(null)
            ->setParkingBrakeTestType(BrakeTestTypeCode::ROLLER)
            ->setWeightType('vsi')
            ->setVehicleWeight('1400')
            ->setWeightIsUnladen(true)
            ->setServiceBrakeIsSingleLine(true)
            ->setIsCommercialVehicle(true)
            ->setIsSingleInFront(true)
            ->setIsParkingBrakeOnTwoWheels(false)
            ->setServiceBrakeControlsCount(1)
            ->setNumberOfAxles(2)
            ->setParkingBrakeNumberOfAxles(1);
    }

    /**
     * @return BrakeTestConfigurationClass3AndAboveDto
     */
    private function getDefaultDto()
    {
        return (new BrakeTestConfigurationClass3AndAboveDto())
            ->setServiceBrake1TestType(BrakeTestTypeCode::ROLLER)
            ->setServiceBrake2TestType(null)
            ->setParkingBrakeTestType(BrakeTestTypeCode::ROLLER)
            ->setWeightType('vsi')
            ->setWeightIsUnladen(false)
            ->setServiceBrakeIsSingleLine(false)
            ->setIsCommercialVehicle(false)
            ->setIsSingleInFront(true)
            ->setIsParkingBrakeOnTwoWheels(false)
            ->setServiceBrakeControlsCount(1)
            ->setNumberOfAxles(2)
            ->setParkingBrakeNumberOfAxles(1)
            ->setVehicleWeight('');
    }

    /**
     * @param $vehicleWeight
     *
     * @return MotTestDto
     */
    private function getMotTestWithVehicleWithPresetWeight($vehicleWeight)
    {
        $vehicleClass = (new VehicleClassDto())->setCode(VehicleClassCode::CLASS_4);
        $vehicle = (new VehicleDto())->setVehicleClass($vehicleClass)->setWeight($vehicleWeight);
        $motTest = (new MotTestDto())->setVehicle($vehicle);
        return $motTest;
    }
}
