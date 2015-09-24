<?php

namespace DvsaCommonTest\Dto\Site;

use DvsaCommon\Constants\FacilityTypeCode;
use DvsaCommon\Dto\Site\FacilityDto;
use DvsaCommon\Dto\Site\FacilityTypeDto;
use DvsaCommon\Dto\Site\VehicleTestingStationDto;
use DvsaCommonTest\Dto\AbstractDtoTester;

/**
 * Unit test for class VehicleTestingStationDto
 *
 * @package DvsaCommonTest\Dto\Common
 */
class VehicleTestingStationDtoTest extends AbstractDtoTester
{
    protected $dtoClassName = VehicleTestingStationDto::class;

    /**
     * @dataProvider dataProviderGetOptlCount
     * @param $inputFacilityCount
     * @param $expectedOutput
     */
    public function testGetTptlCount($mergetAtlAndOptl, $inputFacilityCount, $expectedOutput)
    {
        $vtsDto = $this->createVtsDto(
            $inputFacilityCount[FacilityTypeCode::AUTOMATED_TEST_LANE],
            $inputFacilityCount[FacilityTypeCode::ONE_PERSON_TEST_LANE],
            $inputFacilityCount[FacilityTypeCode::TWO_PERSON_TEST_LANE]
        );

        $actualResult = $vtsDto->getTptlCount();

        $this->assertSame(
            $expectedOutput[FacilityTypeCode::TWO_PERSON_TEST_LANE],
            $actualResult
        );
    }

    /**
     * @dataProvider dataProviderGetOptlCount
     * @param $mergetAtlAndOptl
     * @param $inputFacilityCount
     * @param $expectedOutput
     */
    public function testGetOptlCount($mergetAtlAndOptl, $inputFacilityCount, $expectedOutput)
    {
        $vtsDto = $this->createVtsDto(
            $inputFacilityCount[FacilityTypeCode::AUTOMATED_TEST_LANE],
            $inputFacilityCount[FacilityTypeCode::ONE_PERSON_TEST_LANE],
            $inputFacilityCount[FacilityTypeCode::TWO_PERSON_TEST_LANE]
        );

        $actualResult = $vtsDto->getOptlCount($mergetAtlAndOptl);

        $this->assertSame(
            $expectedOutput[FacilityTypeCode::ONE_PERSON_TEST_LANE],
            $actualResult
        );
    }

    public function dataProviderGetOptlCount()
    {
        return
        [
            [
                'mergetAtlAndOptl' => true,
                'inputFacilityCount' => [
                    FacilityTypeCode::AUTOMATED_TEST_LANE => 1,
                    FacilityTypeCode::ONE_PERSON_TEST_LANE => 1,
                    FacilityTypeCode::TWO_PERSON_TEST_LANE => 1,
                ],
                'expectedOutput' => [
                    FacilityTypeCode::ONE_PERSON_TEST_LANE => 2,
                    FacilityTypeCode::TWO_PERSON_TEST_LANE => 1,
                ]

            ],
            [
                'mergetAtlAndOptl' => false,
                'inputFacilityCount' => [
                    FacilityTypeCode::AUTOMATED_TEST_LANE => 1,
                    FacilityTypeCode::ONE_PERSON_TEST_LANE => 1,
                    FacilityTypeCode::TWO_PERSON_TEST_LANE => 1,
                ],
                'expectedOutput' => [
                    FacilityTypeCode::ONE_PERSON_TEST_LANE => 1,
                    FacilityTypeCode::TWO_PERSON_TEST_LANE => 1,
                ]

            ],
            [
                'mergetAtlAndOptl' => false,
                'inputFacilityCount' => [
                    FacilityTypeCode::AUTOMATED_TEST_LANE => 1,
                    FacilityTypeCode::ONE_PERSON_TEST_LANE => 0,
                    FacilityTypeCode::TWO_PERSON_TEST_LANE => 0,
                ],
                'expectedOutput' => [
                    FacilityTypeCode::ONE_PERSON_TEST_LANE => 0,
                    FacilityTypeCode::TWO_PERSON_TEST_LANE => 0,
                ]

            ],
            [
                'mergetAtlAndOptl' => true,
                'inputFacilityCount' => [
                    FacilityTypeCode::AUTOMATED_TEST_LANE => 1,
                    FacilityTypeCode::ONE_PERSON_TEST_LANE => 0,
                    FacilityTypeCode::TWO_PERSON_TEST_LANE => 0,
                ],
                'expectedOutput' => [
                    FacilityTypeCode::ONE_PERSON_TEST_LANE => 1,
                    FacilityTypeCode::TWO_PERSON_TEST_LANE => 0,
                ]

            ],
            [
                'mergetAtlAndOptl' => true,
                'inputFacilityCount' => [
                    FacilityTypeCode::AUTOMATED_TEST_LANE => 0,
                    FacilityTypeCode::ONE_PERSON_TEST_LANE => 0,
                    FacilityTypeCode::TWO_PERSON_TEST_LANE => 0,
                ],
                'expectedOutput' => [
                    FacilityTypeCode::ONE_PERSON_TEST_LANE => 0,
                    FacilityTypeCode::TWO_PERSON_TEST_LANE => 0,
                ]

            ],
            [
                'mergetAtlAndOptl' => true,
                'inputFacilityCount' => [
                    FacilityTypeCode::AUTOMATED_TEST_LANE => 2,
                    FacilityTypeCode::ONE_PERSON_TEST_LANE => 2,
                    FacilityTypeCode::TWO_PERSON_TEST_LANE => 5,
                ],
                'expectedOutput' => [
                    FacilityTypeCode::ONE_PERSON_TEST_LANE => 4,
                    FacilityTypeCode::TWO_PERSON_TEST_LANE => 5,
                ]

            ],
        ];
    }
    /**
     * @param int $atl
     * @param int $optl
     * @param int $tptl
     * @return VehicleTestingStationDto
     */
    private function createVtsDto($atl = 0, $optl = 0, $tptl = 0)
    {
        $facilityDtos = [];

        for($i = 0; $i < $atl; $i++){
            $facilityDto = $this->createFacilityDto(FacilityTypeCode::AUTOMATED_TEST_LANE);
            if(!isset($facilityDtos[FacilityTypeCode::AUTOMATED_TEST_LANE])){
                $facilityDtos[FacilityTypeCode::AUTOMATED_TEST_LANE] = [];
            }
            $facilityDtos[FacilityTypeCode::AUTOMATED_TEST_LANE][] = $facilityDto;
        }

        for($i = 0; $i < $optl; $i++){
            $facilityDto = $this->createFacilityDto(FacilityTypeCode::ONE_PERSON_TEST_LANE);
            if(!isset($facilityDtos[FacilityTypeCode::ONE_PERSON_TEST_LANE])){
                $facilityDtos[FacilityTypeCode::ONE_PERSON_TEST_LANE] = [];
            }
            $facilityDtos[FacilityTypeCode::ONE_PERSON_TEST_LANE][] = $facilityDto;
        }

        for($i = 0; $i < $tptl; $i++){
            $facilityDto = $this->createFacilityDto(FacilityTypeCode::TWO_PERSON_TEST_LANE);
            if(!isset($facilityDtos[FacilityTypeCode::TWO_PERSON_TEST_LANE])){
                $facilityDtos[FacilityTypeCode::TWO_PERSON_TEST_LANE] = [];
            }
            $facilityDtos[FacilityTypeCode::TWO_PERSON_TEST_LANE][] = $facilityDto;
        }

        $dto = (new VehicleTestingStationDto())
            ->setFacilities($facilityDtos)
        ;

        return $dto;
    }

    private function createFacilityDto($facilityTypeCode)
    {
        $facilityDto = (new FacilityDto())
            ->setType(
                (new FacilityTypeDto())->setCode($facilityTypeCode)
            )
        ;

        return $facilityDto;
    }

}
