<?php

namespace DvsaCommonTest\Dto\Vehicle;

use DvsaCommon\Dto\Vehicle\MakeDto;
use DvsaCommon\Dto\Vehicle\ModelDetailDto;
use DvsaCommon\Dto\Vehicle\ModelDto;
use DvsaCommonTest\Dto\AbstractDtoTester;

/**
 * Unit test for ModelDetailDto
 *
 * @package DvsaCommonTest\Dto\Vehicle
 */
class ModelDetailDtoTest extends AbstractDtoTester
{
    protected $dtoClassName = ModelDetailDto::class;

    public function providerGettersAndSetters()
    {
        $testMethods = parent::providerGettersAndSetters();

        array_push(
            $testMethods,
            ['make', (new MakeDto())->setId(1)],
            ['model', (new ModelDto())->setId(2)]
        );

        return $testMethods;
    }
}
