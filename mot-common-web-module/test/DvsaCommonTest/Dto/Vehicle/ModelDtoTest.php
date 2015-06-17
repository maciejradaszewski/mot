<?php

namespace DvsaCommonTest\Dto\Vehicle;

use DvsaCommon\Dto\Vehicle\MakeDto;
use DvsaCommon\Dto\Vehicle\ModelDto;
use DvsaCommonTest\Dto\AbstractDtoTester;

/**
 * Unit test for class ModelDto
 *
 * @package DvsaCommonTest\Dto\Vehicle
 */
class ModelDtoTest extends AbstractDtoTester
{
    protected $dtoClassName = ModelDto::class;

    public function providerGettersAndSetters()
    {
        $testMethods = parent::providerGettersAndSetters();

        array_push(
            $testMethods,
            ['make', (new MakeDto())->setId(1)]
        );

        return $testMethods;
    }
}
