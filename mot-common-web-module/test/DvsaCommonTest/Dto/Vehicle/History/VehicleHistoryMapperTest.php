<?php

namespace DvsaCommonTest\Dto\Vehicle\History;

use DvsaCommon\Dto\Vehicle\History\VehicleHistoryMapper;

class VehicleHistoryMapperTest extends \PHPUnit_Framework_TestCase
{
    public function testFromArrayToDtoAndFromDtoToArray()
    {
        $inputData =
            [
                [
                    'id'            => 31,
                    'status'        => 'PASSED',
                    'issuedDate'    => '2014-11-03T11:00:00Z',
                    'motTestNumber' => '1234567890031',
                    'testType'      => 'RT',
                    'allowEdit'     => true,
                    'site'          =>
                        [
                            'id'      => 1,
                            'name'    => 'Garage1',
                            'address' => 'Garage1 address'
                        ]
                ],
                [
                    'id'            => 33,
                    'status'        => 'FAILED',
                    'issuedDate'    => '2014-11-03T11:00:00Z',
                    'motTestNumber' => '1234567890033',
                    'testType'      => 'RT',
                    'allowEdit'     => false,
                    'site'          =>
                        [
                            'id'      => 2,
                            'name'    => 'Garage2',
                            'address' => 'Garage2 address'
                        ]
                ]
            ];

        $mapper = new VehicleHistoryMapper();
        $dto = $mapper->fromArrayToDto($inputData, 0);
        $resultData = $mapper->fromDtoToArray($dto);

        $this->assertEquals($inputData, $resultData);
    }
}
