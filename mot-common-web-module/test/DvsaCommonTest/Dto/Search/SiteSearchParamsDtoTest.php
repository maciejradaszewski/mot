<?php

namespace DvsaCommonTest\Dto\Search;

use DvsaCommon\Dto\Search\SiteSearchParamsDto;
use DvsaCommonTest\Dto\AbstractDtoTester;

/**
 * Unit test for class SiteSearchParamsDto
 *
 * @package DvsaCommonTest\Dto\Search
 */
class SiteSearchParamsDtoTest extends AbstractDtoTester
{
    protected $dtoClassName = SiteSearchParamsDto::class;

    public function testToQueryParams()
    {
        $dto = new SiteSearchParamsDto();

        $this->assertEquals(
            [
                'rowCount' => 10,
                'pageNumber' => 1,
            ],
            $dto->toQueryParams()->toArray()
        );
    }
}
