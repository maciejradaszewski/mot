<?php

namespace DvsaCommonTest\Model;

use DvsaCommon\Model\VehicleClassGroup;

class VehicleClassGroupTest extends \PHPUnit_Framework_TestCase
{
    private $model;

    /**
     * @dataProvider dataProvider
     */
    public function testVehicleClassGroup($class, $method, $expectedResult)
    {
        if ($expectedResult) {
            $this->assertTrue(VehicleClassGroup::$method($class));
        } else {
            $this->assertFalse(VehicleClassGroup::$method($class));
        }
    }

    public function dataProvider()
    {
        return [
            [
                'class1',
                'isGroupA',
                true,
            ],
            [
                'class2',
                'isGroupA',
                true,
            ],
            [
                'class3',
                'isGroupA',
                false,
            ],
            [
                'class4',
                'isGroupA',
                false,
            ],
            [
                'class5',
                'isGroupA',
                false,
            ],
            [
                'class7',
                'isGroupA',
                false,
            ],
            [
                'class1',
                'isGroupB',
                false,
            ],
            [
                'class2',
                'isGroupB',
                false,
            ],
            [
                'class3',
                'isGroupB',
                true,
            ],
            [
                'class4',
                'isGroupB',
                true,
            ],
            [
                'class5',
                'isGroupB',
                true,
            ],
            [
                'class7',
                'isGroupB',
                true,
            ],
        ];
    }
}
