<?php

namespace DvsaCommonTest\Dto\Search;

use DvsaCommon\Constants\SearchParamConst;
use DvsaCommon\Dto\Search\SearchParamsDto;
use DvsaCommonTest\Dto\AbstractDtoTester;
use Zend\Stdlib\Parameters;

/**
 * Unit test for class SearchParamsDto
 *
 * @package DvsaCommonTest\Dto\Search
 */
class SearchParamsDtoTest extends AbstractDtoTester
{
    protected $dtoClassName = SearchParamsDto::class;

    /**
     * @dataProvider dataProvidetTestToQueryParams
     */
    public function testToQueryParams(SearchParamsDto $dto, array $expect)
    {
        $actual = $dto->toQueryParams();

        $this->assertEquals(
            new Parameters(
                [
                    SearchParamConst::ROW_COUNT => SearchParamConst::DEF_ROWS_COUNT,
                    SearchParamConst::PAGE_NR   => SearchParamConst::DEF_PAGE_NR,
                ] + $expect
            ),
            $actual
        );
    }

    public function dataProvidetTestToQueryParams()
    {
        $dto = new SearchParamsDto();

        return [
            [
                'dto'    => $dto,
                'params' => [],
            ],
            [
                'dto'    => $this->cloneObj($dto)
                    ->setSortBy('sortByKey')
                    ->setSortDirection(SearchParamConst::SORT_DIRECTION_DESC),
                'params' => [
                    SearchParamConst::SORT_BY => 'sortByKey',
                    SearchParamConst::SORT_DIRECTION => SearchParamConst::SORT_DIRECTION_DESC,
                ],
            ],
        ];
    }

    /**
     * @param $obj
     *
     * @return SearchParamsDto
     */
    private function cloneObj($obj)
    {
        return clone $obj;
    }
}
