<?php

namespace DvsaCommonTest\Model;

use DvsaCommon\Model\VehicleClassGroup;

class VehicleClassGroupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider dataProviderForClassGroupA
     */
    public function testVehicleClassGroupA($class, $expectedResult)
    {
        $this->assertEquals($expectedResult, VehicleClassGroup::isGroupA($class));
    }

    /**
     * @dataProvider dataProviderForClassGroupB
     */
    public function testVehicleClassGroupB($class, $expectedResult)
    {
        $this->assertEquals($expectedResult, VehicleClassGroup::isGroupB($class));
    }

    public function dataProviderForClassGroupB()
    {
        return [
            ['1', false],
            ['2', false],
            ['3', true],
            ['4', true],
            ['5', true],
            ['7', true],
        ];
    }

    public function dataProviderForClassGroupA()
    {
        return [
            ['1', true],
            ['2', true],
            ['3', false],
            ['4', false],
            ['5', false],
            ['7', false],
        ];
    }
}
