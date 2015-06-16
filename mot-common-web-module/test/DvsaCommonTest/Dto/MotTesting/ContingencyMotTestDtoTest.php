<?php

namespace DvsaCommonTest\Dto\MotTesting;

use DvsaCommon\Dto\MotTesting\ContingencyMotTestDto;
use DvsaCommonTest\Dto\AbstractDtoTester;

/**
 * Unit test for class ContingencyMotTest
 *
 * @package DvsaCommonTest\Dto\MotTesting
 */
class ContingencyMotTestDtoTest extends AbstractDtoTester
{
    protected $dtoClassName = ContingencyMotTestDto::class;

    public function testFilterDateFormat()
    {
        $dto = new ContingencyMotTestDto();

        // test date filter
        $this->assertEquals('2014-01-01', $dto->setPerformedAt('2014-1-1')->getPerformedAt());
    }
}
