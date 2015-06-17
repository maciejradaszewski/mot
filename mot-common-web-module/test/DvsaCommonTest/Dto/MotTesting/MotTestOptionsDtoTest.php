<?php

namespace DvsaCommonTest\Dto\MotTesting;

use DvsaCommon\Dto\MotTesting\MotTestOptionsDto;

class MotTestOptionsDtoTest extends \PHPUnit_Framework_TestCase
{
    public function testFromArrayToArray()
    {
        $expected = [
            'startedDate' => 'not relevant',
            'vehicle'     => [
                'make'                      => 'not relevant',
                'model'                     => 'not relevant',
                'vehicleRegistrationNumber' => 'not relevant',
            ]
        ];

        $dto = MotTestOptionsDto::fromArray($expected);

        $this->assertEquals($expected, $dto->toArray());
    }
}
